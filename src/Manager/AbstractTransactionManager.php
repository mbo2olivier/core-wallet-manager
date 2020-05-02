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
use Mukadi\Wallet\Core\Exception\TransactionException;
use Mukadi\Wallet\Core\Storage\TransactionStorageLayer;
use Mukadi\Wallet\Core\TransactionInterface;
use Mukadi\Wallet\Core\TransactionHistoryInterface;

/**
 * Class AbstractTransactionManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class AbstractTransactionManager
{
    protected $storage;
    protected $hClass;

    public function __construct(TransactionStorageLayer $storage, $historyClass) {
        $this->storage = $storage;
        $this->hClass = $historyClass;
    }

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     * @throws \Mukadi\Wallet\Core\Exception\StorageLayerException
     */
    public function open(TransactionInterface $tx) {
        $tx->setTransactionId($this->generateIdFor($tx));
        $tx = $this->beforeOpen($tx);

        $tx->setBeginAt(new \DateTime('now'));
        $tx->setStatus(Codes::TX_STATUS_OPENED);

        $tx = $this->storage->saveTransaction($tx);
        $this->capture($tx);

        return $this->afterOpen($tx);
    }

    public function close($txId, $status = Codes::TX_STATUS_CANCELED) {
        $tx = $this->storage->findTransactionBy(['transactionId' => $txId]);
        if($tx == null) {
            throw new TransactionException("cannot find transaction with id: " . $txId);
        }

        if($tx->getStatus() !== Codes::TX_STATUS_OPENED) {
            throw new TransactionException("Transaction already closed");
        }

        if(!in_array($status, [Codes::TX_STATUS_CANCELED, Codes::TX_STATUS_TERMINATED])) {
            throw new TransactionException(sprintf("'%s' is not a valid transaction status", $status));
        }

        $tx = $this->beforeClose($tx);
        $tx->setStatus($status);
        $tx->setEndedAt(new \DateTime('now'));
        $tx = $this->storage->saveTransaction($tx);
        $this->capture($tx);
        return $this->afterClose($tx);
    }

    protected function capture(TransactionInterface $tx) {
        $class = $this->hClass;
        /** @var TransactionHistoryInterface $h */
        $h = new $class();
        $h->setDate(new \DateTime('now'));
        $h->setTransactionId($tx->getTransactionId());
        $h->setStatus($tx->getStatus());
        $h->setNote($tx->getNote());
        $this->storage->saveHistory($h);
    }

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public abstract function beforeClose(TransactionInterface $tx);

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public abstract function afterClose(TransactionInterface $tx);

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public abstract function beforeOpen(TransactionInterface $tx);

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public abstract function afterOpen(TransactionInterface $tx);

    /**
     * @param TransactionInterface $tx
     * @return string
     */
    public abstract function generateIdFor(TransactionInterface $tx);
}
