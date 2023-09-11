<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Manager;

use Mukadi\Wallet\Core\AuthorizationBatch;
use Mukadi\Wallet\Core\Exception\AuthorizationException;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\BatchInterface;
use Mukadi\Wallet\Core\EntryInterface;
use Mukadi\Wallet\Core\Exception\EntryException;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
use Mukadi\Wallet\Core\Operation;
use Mukadi\Wallet\Core\ProcessingEntry;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;

/**
 * Class AbstractWalletManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class AbstractWalletManager  
{
    /** @var WalletStorageLayer $storage  */
    protected $storage;
    /** @var string */
    protected $authClass;
    /** @var  AbstractSchemaManager */
    protected $schema;

    public function __construct(AbstractSchemaManager $schema,WalletStorageLayer $storage, $authClass) {
        $this->storage = $storage;
        $this->authClass = $authClass;
        $this->schema = $schema;
    }

    /**
     * open a new wallet
     *
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     * @throws StorageLayerException
     **/
    public function openWallet(WalletInterface $wallet): WalletInterface
    {
        $wallet->setWalletId($this->generateWalletIdFor($wallet));
        $wallet = $this->beforeOpenWallet($wallet);
        
        $wallet->setCreatedAt(new \DateTimeImmutable('now'));
        $wallet->setClosed(false);

        $wallet = $this->storage->saveWallet($wallet);

        return $this->afterOpenWallet($wallet);
    }

    /**
     * close a wallet
     *
     * @param string $walletId
     * @return WalletInterface
     * @throws WalletException
     * @throws StorageLayerException
     **/
    public function closeWallet(string $walletId): WalletInterface
    {
        $wallet = $this->storage->getWallet($walletId);
        if($wallet == null)
            throw new WalletException("cannot find wallet with Id : ".$walletId);
        if($wallet->isClosed())
            throw new WalletException('Wallet already closed');

        $wallet = $this->beforeCloseWallet($wallet);
        
        $wallet->setClosedAt(new \DateTimeImmutable('now'));
        $wallet->setClosed(true);

        $wallet = $this->storage->saveWallet($wallet);

        return $this->afterCloseWallet($wallet);
    }

    /**
     * @param Operation $r
     * @return AuthorizationInterface
     * @throws AuthorizationException
     */
    public function authorize(Operation $r): AuthorizationInterface {
        $auth = $this->storage->findPreviousAuthorization($r->getAuthorizationRequestId(), $r->getOperationCode());
        if (null !== $auth && $auth->getStatus() !== Codes::AUTH_STATUS_PENDING) {
            return $auth;
        }

        if (null === $auth) {
            $class = $this->authClass;
            /** @var AuthorizationInterface $auth */
            $auth = new $class();
            $auth->setDate(new \DateTimeImmutable('now'));
            $auth->setOperationCode($r->getOperationCode());
            $auth->setOperationId($r->getOperationId());
            $auth->setSchemaId($r->getSchemaId());
            $auth->setAuthorizationRequestId($r->getAuthorizationRequestId());
            $auth->setPlatformId($r->getPlatformId());
            $auth->setHolderId($r->getHolderId());
            $auth->setDescription($r->getDescription());
            $auth->setAuthorizationId($this->getNextAuthorizationId());
            $auth->setTransactionAmount($r->getTransactionAmount());
            $auth->setCurrency($r->getCurrency());
            $auth->setCommissionAmount($r->getCommissionAmount());
            $auth->setCommissionCurrency($r->getCommissionCurrency());
            $auth->setStatus(Codes::AUTH_STATUS_PENDING);
            
            $auth = $this->beforeAuthorizationInit($auth);
            $auth = $this->storage->saveAuthorization($auth);
            $auth = $this->afterAuthorizationInit($auth);
        }

        $ops = $this->schema->getSchemaFor($r);
        $batch = new AuthorizationBatch($auth, $ops, $r->hasDoubleEntrySupport());
        
        $auth = $this->run($batch, $auth);

        $r->setAuthorizationId($auth->getAuthorizationId());
        $this->onOperationAuthorized($r, $auth);
        return $auth;
    }

    public function run(BatchInterface $batch, AuthorizationInterface $auth = null): AuthorizationInterface {

        if (null === $auth) {
            $auth = $batch->buildAuthorization();
            $auth->setAuthorizationId($this->getNextAuthorizationId());
            $auth->setDate(new \DateTimeImmutable('now'));
            $auth->setStatus(Codes::AUTH_STATUS_PENDING);

            $auth = $this->beforeAuthorizationInit($auth);
            $auth = $this->storage->saveAuthorization($auth);
            $auth = $this->afterAuthorizationInit($auth);
        }
        elseif(Codes::AUTH_STATUS_PENDING !== $auth->getStatus()) {
            throw new AuthorizationException($auth, 'cannot process a non pending authorization');
        }

        try {
            $this->storage->beginTransaction();

            $entries = $batch->getEntries();
            
            $ids = array_unique(array_map(fn(EntryInterface $e) => $e->getWalletId(), $entries));

            $wallets = $this->storage->findAllWalletsById($ids);
            /** @var array<ProcessingEntry> */
            $processings = [];

            foreach ($entries as $e) {
                if (!isset($wallets[$e->getWalletId()])) {
                    throw new EntryException(sprintf('cannot find wallet with id %s', $e->getWalletId()));
                }

                $processings[] = new ProcessingEntry($e, $wallets[$e->getWalletId()]);
            }

            $processings = $this->beforeEntryProcessing($processings);

            $balances = [];
            foreach($processings as $ew) {
                $op = $ew->entry;
                $op->setAuthorizationId($auth->getAuthorizationId());
                $op->setDate(new \DateTimeImmutable('now'));
                $op->setPlatformId($auth->getPlatformId());

                $balance = isset($balances[$op->getCurrency()]) ? $balances[$op->getCurrency()] : 0;
                $balance += ($op->getType() == Codes::OPERATION_TYPE_CASH_IN ? 1 : -1) * $op->getAmount();
                $balances[$op->getCurrency()] = $balance;

                $this->execute($op, $ew->wallet);
            }

            if ($batch->isDoubleEntry() && \count(array_filter($balances, fn ($b) => $b != 0)) > 0) {
                throw new AuthorizationException($auth, "your entries are not balanced");
            }

            $auth->setStatus(Codes::AUTH_STATUS_ACCEPTED);
            $this->storage->saveAuthorization($auth, false);
            
            $this->storage->commit();
            $this->onAuthorizationAccepted($auth);

            return $auth;
        }
        catch(AuthorizationException $e) {
            $this->storage->rollback();

            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw $e;
        }
        catch(EntryException $e) {
            $this->storage->rollback();

            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw new AuthorizationException($auth, $e->getMessage(), $e);
        }
        catch(\Exception $e) {
            $this->storage->rollback();

            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw new AuthorizationException($auth, "an error occured while processing yuour request", $e);
        }
    }

    /**
     * Execute an operation
     *
     * @param EntryInterface $op
     * @throws EntryException
     * @return EntryInterface
     */
    protected  function execute(EntryInterface $op, WalletInterface $wallet): EntryInterface {

        if (!$op->getCurrency()) {
            throw new EntryException(sprintf("'%s' is not a valid currency", $op->getCurrency()), $op);
        }

        if ($wallet->getCurrency() !== $op->getCurrency()) {
            throw new EntryException("Wallet currency mismatch", $op);
        }

        if (($op->getAmount() == "") || 0 > $op->getAmount()) {
            throw new EntryException(sprintf("'%s' is not a valid amount", $op->getAmount()), $op);
        }

        if (($wallet->getCurrency() !== $op->getTransactionCurrency()) && !$op->getAppliedRate()) {
            throw new EntryException(sprintf("applied exchange rate not defined", $op->getCurrency()), $op);
        }

        $this->beforeExecuteOperation($op, $wallet);

        $outcome = $op->getType() === Codes::OPERATION_TYPE_CASH_IN ? ($wallet->getBalance() + $op->getAmount()) : ($wallet->getBalance() - $op->getAmount());
        $op->setExecutedAt(new \DateTimeImmutable('now'));
        
        $wallet->setBalance($outcome);
        $wallet->setBalanceUpdatedAt(new \DateTimeImmutable('now'));
        $op->setBalance($outcome);
        
        $this->storage->saveEntry($op, false);
        $this->storage->saveWallet($wallet, false);
        
        $this->afterExecuteOperation($op);

        return $op;
    }

    /**
     * @param ProcessingEntry[] $entries
     * @return ProcessingEntry[]
     * @throws EntryException
     */
    protected function beforeEntryProcessing(array $entries): array {
        return $entries;
    }

    /**
     * @param EntryInterface $op 
     * @param WalletInterface $w
     * @throws EntryException
     */
    protected function beforeExecuteOperation(EntryInterface $op, WalletInterface $w) {}

    /**
     * @param EntryInterface $op 
     */
    protected function afterExecuteOperation(EntryInterface $op) {}

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    protected function beforeOpenWallet(WalletInterface $wallet): WalletInterface {
        return $wallet;
    }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    protected function afterOpenWallet(WalletInterface $wallet): WalletInterface {
        return $wallet;
    }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    protected function beforeCloseWallet(WalletInterface $wallet):WalletInterface {
        return $wallet;
    }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    protected function afterCloseWallet(WalletInterface $wallet): WalletInterface {
        return $wallet;
    }
    /**
     * generate a new free authorization identifier
     * 
     * @return string
     */
    protected abstract function getNextAuthorizationId(): string;

    /**
     * @param WalletInterface $wallet
     * @return string
     */
    protected abstract function generateWalletIdFor(WalletInterface $wallet): string;

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    protected function beforeAuthorizationInit(AuthorizationInterface $auth): AuthorizationInterface {
        return $auth;
    }

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    protected function afterAuthorizationInit(AuthorizationInterface $auth): AuthorizationInterface {
        return $auth;
    }

    /**
     * @param AuthorizationInterface $auth
     */
    protected function onAuthorizationRefused(AuthorizationInterface $auth) {}

    /**
     * @param AuthorizationInterface $auth
     */
    protected function onAuthorizationAccepted(AuthorizationInterface $auth) {}

    /**
     * @param Operation $r
     * @param AuthorizationInterface $auth
     */
    protected function onOperationAuthorized(Operation $r, AuthorizationInterface $auth) {}
}
