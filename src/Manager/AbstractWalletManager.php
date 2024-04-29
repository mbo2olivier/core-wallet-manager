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
use Mukadi\Wallet\Core\Lien;
use Mukadi\Wallet\Core\Exception\EntryException;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
use Mukadi\Wallet\Core\Operation;
use Mukadi\Wallet\Core\OperationExecutionIntent;
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

    public function run(BatchInterface $batch, ?AuthorizationInterface $auth = null): AuthorizationInterface {

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
            $self = $this;

            $auth = $this->storage->transactional(function (WalletStorageLayer $storage) use ($batch, $auth, $self) {
                $entries = $batch->getEntries();
            
                $ids = array_unique(array_map(fn(EntryInterface $e) => $e->getWalletId(), $entries));

                $wallets = $storage->findAllWalletsById($ids);
                /** @var array<ProcessingEntry> */
                $processings = [];

                foreach ($entries as $e) {
                    if (!isset($wallets[$e->getWalletId()])) {
                        throw new EntryException(sprintf('cannot find wallet with id %s', $e->getWalletId()));
                    }

                    $processings[] = new ProcessingEntry($e, $wallets[$e->getWalletId()]);
                }

                $processings = $self->beforeEntryProcessing($processings, $auth);

                $balances = array_reduce($entries, function (array $b, EntryInterface $e) {
                    $balance = isset($b[$e->getCurrency()]) ? $b[$e->getCurrency()] : 0;
                    $balance += ($e->getType() == Codes::OPERATION_TYPE_CASH_IN ? 1 : -1) * $e->getAmount();
                    $b[$e->getCurrency()] = $balance;

                    return $b;
                }, []);

                if ($batch->isDoubleEntry() && \count(array_filter($balances, fn ($b) => $b != 0)) > 0) {
                    throw new AuthorizationException($auth, "your entries are not balanced");
                }

                foreach($processings as $ew) {
                    $op = $ew->entry;
                    $op->setAuthorizationId($auth->getAuthorizationId());
                    $op->setOperationCode($auth->getOperationCode());
                    $op->setOperationId($auth->getOperationId());
                    $op->setDate(new \DateTimeImmutable('now'));
                    $op->setPlatformId($auth->getPlatformId());
                    $op->setExchangeRate($auth->getExchangeRate());

                    $self->execute($op, $ew->wallet);
                }

                $auth->setStatus(Codes::AUTH_STATUS_ACCEPTED);
                $storage->saveAuthorization($auth, false);
                
                return $auth;
            });

            $this->onAuthorizationAccepted($auth);
            return $auth;

        }
        catch(AuthorizationException $e) {
            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw $e;
        }
        catch(EntryException $e) {
            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw new AuthorizationException($auth, $e->getMessage(), $e);
        }
        catch(\Exception $e) {
            $auth->setStatus(Codes::AUTH_STATUS_REFUSED);
            $this->storage->saveAuthorization($auth);
            $this->onAuthorizationRefused($auth);

            throw new AuthorizationException($auth, "an error occured while processing your request", $e);
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

        $liens = $this->storage->getRelatedSortedActiveLiens($wallet->getWalletId());
        $retainedLiens = [];
        $remainingAmount = $op->getAmount();

        foreach ($liens as $lien) {

            if ($lien->getStatus() !== Codes::LIEN_STATUS_ACTIVE) {
                throw new EntryException("'{$lien->getReason()}' is not an active lien", $op);
            }

            if ($op->getType() === Codes::OPERATION_TYPE_CASH_IN) {
                $retainedLiens[] = $lien;
                continue;
            }

            if (!$lien->getOperationCode()) {
                $retainedLiens[] = $lien;
                continue;
            }
            
            if ($lien->getOperationCode() != $op->getOperationCode()) {
                $retainedLiens[] = $lien;
                continue;
            }

            if ($lien->getOperationId() && $lien->getOperationId() !== $op->getOperationId()) {
                $retainedLiens[] = $lien;
                continue;
            }

            if ($remainingAmount <= 0) {
                continue;
            }

            if ($remainingAmount >= $lien->getAmount()) {
                $lamt = $lien->getAmount();
                $this->storage->markReadyForConsumption($lien, $op->getAuthorizationId(), $op->getSerialId());
                $remainingAmount -= $lamt;
            }
            else {
                $this->storage->markReadyForConsumption($lien, $op->getAuthorizationId(), $op->getSerialId(), $remainingAmount);
                $remainingAmount = 0;
            }

        }
        

        $availableBalance = array_reduce($retainedLiens, fn ($b, Lien $l) => $b - $l->getAmount(), $wallet->getBalance());
        $this->beforeExecuteOperation(new OperationExecutionIntent($op, $wallet, $availableBalance, $retainedLiens));

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
    protected function beforeEntryProcessing(array $entries, ?AuthorizationInterface $auth = null): array {
        return $entries;
    }

    /**
     * @param OperationExecutionIntent $intent 
     * @throws EntryException
     */
    protected function beforeExecuteOperation(OperationExecutionIntent $intent) {}

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
