<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Manager;

use Mukadi\Wallet\Core\Exception\AuthorizationException;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\Exception\BalanceException;
use Mukadi\Wallet\Core\Request;
use Mukadi\Wallet\Core\Reversal;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Exception\OperationException;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
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
    /** @var string */
    protected $opClass;
    /** @var  AbstractSchemaManager */
    protected $schema;

    public function __construct(AbstractSchemaManager $schema,WalletStorageLayer $storage, $authClass, $opClass) {
        $this->storage = $storage;
        $this->authClass = $authClass;
        $this->opClass = $opClass;
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
    public function openWallet(WalletInterface $wallet)
    {
        $wallet->setWalletId($this->generateWalletIdFor($wallet));
        $wallet = $this->beforeOpenWallet($wallet);
        
        $wallet->setCreatedAt(new \DateTime('now'));
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
    public function closeWallet($walletId)
    {
        $wallet = $this->storage->findWalletBy(["walletId" => $walletId]);
        if($wallet == null)
            throw new WalletException("cannot find wallet with Id : ".$walletId);
        if($wallet->isClosed())
            throw new WalletException('Wallet already closed');

        $wallet = $this->beforeCloseWallet($wallet);
        
        $wallet->setClosedAt(new \DateTime('now'));
        $wallet->setClosed(true);

        $wallet = $this->storage->saveWallet($wallet);

        return $this->afterCloseWallet($wallet);
    }

    /**
     * @param Request $r
     * @return AuthorizationInterface
     * @throws BalanceException
     * @throws WalletException
     */
    public function authorize(Request $r) {
        /** @var WalletInterface $wallet */
        $wallet = $this->storage->findWalletBy(["walletId" => $r->getWalletId()]);
        if($wallet == null) {
            throw new WalletException("Cannot find targeted wallet with id : ". $r->getWalletId());
        }

        if($wallet->getCurrency() !== $r->getCurrency()) {
            throw new WalletException("Cannot find targeted wallet with id : ". $r->getWalletId());
        }

        $outcome = $wallet->getBalance() - $r->getAmount();
        if($outcome >= 0) {
            $class = $this->opClass;
            /** @var OperationInterface $op */
            $op = new $class();
            $op->setReversal(false);
            $op->setAmount($r->getAmount());
            $op->setCurrency($r->getCurrency());
            $op->setLabel($r->getLabel());
            $op->setWalletId($r->getWalletId());
            $op->setDate(new \DateTime('now'));
            $op->setMaker($r->getRequester());
            $op->setType(Codes::OPERATION_TYPE_CASH_OUT);
            $op->setPlatformId($wallet->getPlatformId());

            /** @var OperationInterface $op2 */
            $op2 = new $class();
            $op2->setReversal(false);
            $op2->setAmount($r->getAmount());
            $op2->setCurrency($r->getCurrency());
            $op2->setLabel($r->getLabel());
            $op2->setWalletId($r->getBufferWalletId());
            $op2->setDate(new \DateTime('now'));
            $op2->setMaker($r->getRequester());
            $op2->setType(Codes::OPERATION_TYPE_CASH_IN);
            $op2->setPlatformId($wallet->getPlatformId());


            $class = $this->authClass;
            /** @var AuthorizationInterface $auth */
            $auth = new $class();
            $auth->setType(Codes::AUTH_TYPE_DEBIT);
            $auth->setAmount($r->getAmount());
            $auth->setCurrency($r->getCurrency());
            $auth->setCode($r->getCode());
            $auth->setAuthorizationRef($r->getAuthorizationRef());
            $auth->setPlatformId($wallet->getPlatformId());
            $auth->setChannelId($r->getChannelId());
            $auth->setAuthorizationId($this->getNextAuthorizationId());
            $auth->setStatus(Codes::AUTH_STATUS_PENDING);
            $auth->setBalance($outcome);
            $auth->setRequester($r->getRequester());
            $auth->setWalletId($r->getWalletId());
            $auth->setBufferWalletId($r->getBufferWalletId());
            $auth = $this->storage->saveAuthorization($auth);

            $op->setAuthorizationId($auth->getAuthorizationId());
            $op2->setAuthorizationId($auth->getAuthorizationId());

            $this->execute($op);
            $this->execute($op2);

            return $auth;
        }
        else
            throw new BalanceException("insufficient balance");
    }

    /**
     * @param string $authId
     * @return AuthorizationInterface
     * @throws AuthorizationException
     * @throws OperationException
     */
    public function authorizationRedemption($authId) {
        $auth = $this->storage->findAuthorizationBy(["authorizationId" => $authId]);

        if($auth == null) {
            throw new AuthorizationException("cannot find authorization with id: ". $authId);
        }

        if($auth->getStatus() !== Codes::AUTH_STATUS_PENDING) {
            throw new AuthorizationException("authorization is not pending");
        }

        if($auth->getType() !== Codes::AUTH_TYPE_DEBIT) {
            throw new AuthorizationException("non debit authorization not allowed");
        }
        $auth = $this->beforeAuthorizationRedemption($auth);
        $ops = $this->schema->getSchemaFor($auth);

        foreach($ops as $op) {
            $op->setStatus(Codes::OPERATION_STATUS_INIT);
            $op->setAuthorizationId($authId);
            $op->setDate(new \DateTime('now'));
            $op->setPlatformId($auth->getPlatformId());
            $op->setMaker($auth->getRequester());
            $op->setReversal(false);

            $this->execute($op);
        }
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);

        $this->storage->saveAuthorization($auth);

        return $this->afterAuthorizationRedemption($auth);
    }

    public function authorizationReversal(Reversal $reversal) {
        $authId = $reversal->getPreviousAuthId();
        $previous = $this->storage->findAuthorizationBy(["authorizationId" => $authId]);

        if($previous == null) {
            throw new AuthorizationException("cannot find authorization to reverse with id: ". $authId);
        }

        if($previous->getStatus() !== Codes::AUTH_STATUS_FINALIZED) {
            throw new AuthorizationException("authorization is not finalized");
        }

        if($previous->getType() !== Codes::AUTH_TYPE_DEBIT) {
            throw new AuthorizationException("reversal of non debit authorization not allowed");
        }
        $class = $this->authClass;
        /** @var AuthorizationInterface $auth */
        $auth = new $class();
        $auth->setType(Codes::AUTH_TYPE_REVERSE);
        $auth->setAmount($previous->getAmount());
        $auth->setCurrency($previous->getCurrency());
        $auth->setCode($previous->getCode());
        $auth->setAuthorizationRef($previous->getAuthorizationRef());
        $auth->setPlatformId($previous->getPlatformId());
        $auth->setChannelId($previous->getChannelId());
        $auth->setAuthorizationId($this->getNextAuthorizationId());
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setBalance($previous->getBalance() + $previous->getAmount());
        $auth->setWalletId($previous->getWalletId());
        $auth->setRequester($reversal->getMaker());
        $this->storage->saveAuthorization($auth);

        $auth = $this->beforeAuthorizationReversal($auth);

        $ops = $this->storage->listOperationBy(["authorizationId" => $previous->getAuthorizationId()]);
        $class = $this->opClass;
        foreach($ops as $o) {
            /** @var OperationInterface $rop */
            $rop = new $class();
            $rop->setAmount($o->getAmount());
            $rop->setCurrency($o->getCurrency());
            $rop->setPlatformId($o->getPlatformId());
            $rop->setType($o->getType() === Codes::OPERATION_TYPE_CASH_OUT ? Codes::OPERATION_TYPE_CASH_IN : Codes::OPERATION_TYPE_CASH_OUT);
            $rop->setLabel($o->getLabel());
            $rop->setReversal(true);
            $rop->setReversedFrom($o->getOperationId());
            $rop->setDate(new \DateTime('now'));
            $rop->setWalletId($o->getWalletId());
            $rop->setStatus(Codes::OPERATION_STATUS_INIT);
            $rop->setAuthorizationId($auth->getAuthorizationId());
            $rop->setMaker($reversal->getMaker());

            $this->execute($rop);
        }

        $previous->setStatus(Codes::AUTH_STATUS_REVERSED);
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);
        $this->storage->saveAuthorization($auth);
        $this->storage->saveAuthorization($previous);

        return $this->afterAuthorizationReversal($auth);
    }

    /**
     * Execute an operation
     *
     * @param OperationInterface $op
     * @throws BalanceException
     * @throws OperationException
     * @throws AuthorizationException
     * @return OperationInterface
     */
    protected  function execute(OperationInterface $op) {
        if(! $op->getOperationId()) {
            $op->setOperationId($this->generateOperationIdFor($op));
        }
        if($this->storage->findOperationBy(["operationId" => $op->getOperationId()])) {
            throw new OperationException("Operation already exist");
        }

        $op->setStatus(Codes::OPERATION_STATUS_INIT);
        $op->setExecutedAt(new \DateTime('now'));
        $op = $this->beforeExecuteOperation($op);
        /** @var WalletInterface $wallet */
        $wallet = $this->storage->findWalletBy(["walletId" => $op->getWalletId()]);
        if($wallet == null) {
            $op->setStatus(Codes::OPERATION_STATUS_ERROR);
            $this->storage->saveOperation($op);
            throw new OperationException("Cannot find targeted operation wallet", $op);
        }

        if($wallet->getCurrency() !== $op->getCurrency()) {
            $op->setStatus(Codes::OPERATION_STATUS_ERROR);
            $this->storage->saveOperation($op);
            throw new OperationException("Wallet currency mismatch", $op);
        }

        $auth = $this->storage->findAuthorizationBy(["authorizationId" => $op->getAuthorizationId()]);
        if($auth == null) {
            $op->setStatus(Codes::OPERATION_STATUS_UNAUTHORIZED);
            $this->storage->saveOperation($op);
            throw new AuthorizationException("unauthorized operation");
        }
        if($auth->getStatus() !== Codes::AUTH_STATUS_PENDING) {
            $op->setStatus(Codes::OPERATION_STATUS_UNAUTHORIZED);
            $this->storage->saveOperation($op);
            throw new AuthorizationException('operation not allowed for non pending status authorization');
        }

        $outcome = $op->getType() === Codes::OPERATION_TYPE_CASH_IN ? ($wallet->getBalance() + $op->getAmount()) : ($wallet->getBalance() - $op->getAmount());
        $op->setValidatedAt(new \DateTime('now'));
        $op->setValidator(Codes::SYSTEM_VALIDATOR);
        $op->setStatus(Codes::OPERATION_STATUS_AUTHORIZED);
        
        $wallet->setBalance($outcome);
        $wallet->setBalanceUpdatedAt(new \DateTime('now'));
        $op->setBalance($outcome);
        $op->setStatus(Codes::OPERATION_STATUS_SUCCESS);
        
        $this->storage->beginTransaction();
        $this->storage->saveOperation($op);
        $this->storage->saveWallet($wallet);
        $this->storage->commit();
        
        return $this->afterExecuteOperation($op);
    }

    /**
     * @param OperationInterface $op 
     * @return OperationInterface
     * @throws OperationException
     */
    public abstract function beforeExecuteOperation(OperationInterface $op);

    /**
     * @param OperationInterface $op 
     * @return OperationInterface
     */
    public abstract function afterExecuteOperation(OperationInterface $op);

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    public abstract function beforeOpenWallet(WalletInterface $wallet);

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    public abstract function afterOpenWallet(WalletInterface $wallet);

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    public abstract function beforeCloseWallet(WalletInterface $wallet);

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    public abstract function afterCloseWallet(WalletInterface $wallet);

    /**
     * Generate an identifier for the given operation
     * 
     * @param OperationInterface $op
     * @return string
     */
    public abstract function generateOperationIdFor(OperationInterface $op);

    /**
     * generate a new free authorization identifier
     * 
     * @return string
     */
    public abstract function getNextAuthorizationId();

    /**
     * @param WalletInterface $wallet
     * @return string
     */
    public abstract function generateWalletIdFor(WalletInterface $wallet);

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public abstract function beforeAuthorizationRedemption(AuthorizationInterface $auth);

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public abstract function afterAuthorizationRedemption(AuthorizationInterface $auth);

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public abstract function beforeAuthorizationReversal(AuthorizationInterface $auth);

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public abstract function afterAuthorizationReversal(AuthorizationInterface $auth);
}
