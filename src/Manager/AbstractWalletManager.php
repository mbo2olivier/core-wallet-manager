<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Manager;

use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Exception\OperationException;
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

    public function __construct(WalletStorageLayer $storage) {
        $this->storage = $storage;
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
        $wallet = $this->beforeOpenWallet($wallet);
        
        $wallet->setCreatedAt(new \DateTime('now'));
        $wallet->setClosed(false);

        $wallet = $this->storage->saveWallet($wallet);

        return $this->afterOpenWallet($wallet);
    }

    /**
     * close a wallet
     *
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     * @throws StorageLayerException
     **/
    public function closeWallet(WalletInterface $wallet)
    {
        $wallet = $this->beforeCloseWallet($wallet);
        
        $wallet->setClosedAt(new \DateTime('now'));
        $wallet->setClosed(true);

        $wallet = $this->storage->saveWallet($wallet);

        return $this->afterCloseWallet($wallet);
    }

    /**
     * Execute an operation
     * 
     * @param OperationInterface $op
     * @return OperationInterface
     * @throws OperationException
     */
    public function execute(OperationInterface $op) {
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
            $op->setStatus(Codes::OPERATION_STATUS_EROR);
            $this->storage->saveOperation($op);
            throw new OperationException("Cannot find targeted operation wallet", $op);
        }

        if($wallet->getCurrency() !== $op->getCurrency()) {
            $op->setStatus(Codes::OPERATION_STATUS_EROR);
            $this->storage->saveOperation($op);
            throw new OperationException("Wallet currency mismatch", $op);
        }

        $outcome = $outcome = $op->getType() === Codes::OPERATION_TYPE_CASHIN ? ($wallet->getBalance() + $op->getAmount()) : ($wallet->getBalance() - $op->getAmount());
        if(! $op->getAuthorizationId()) {
            if($outcome < 0) {
                $op->setStatus(Codes::OPERATION_STATUS_UNAUTHORIZED);
                $this->storage->saveOperation($op);
                throw new OperationException("insufficient balance", $op);
            }
            $op->setAuthorizationId($this->getNextAuthorizationId());
            $op->setValidatedAt(new \DateTime('now'));
            $op->setValidator(Codes::SYSTEM_VALIDATOR);
            $op->setStatus(Codes::OPERATION_STATUS_AUTHORIZED);
        }
        
        $wallet->setBalance($outcome);
        $wallet->setBalancepdatedAt(new \DateTime('now'));
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
}
