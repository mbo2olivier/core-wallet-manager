<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\Manager\AbstractTransactionManager;
use Mukadi\Wallet\Core\TransactionInterface;

class TransactionManager extends AbstractTransactionManager {

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public function beforeClose(TransactionInterface $tx) { return $tx; }

    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public function afterClose(TransactionInterface $tx) { return $tx; }
    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public function beforeOpen(TransactionInterface $tx) { return $tx; }
    /**
     * @param TransactionInterface $tx
     * @return TransactionInterface
     */
    public function afterOpen(TransactionInterface $tx) { return $tx; }

    /**
     * @param TransactionInterface $tx
     * @return string
     */
    public function generateIdFor(TransactionInterface $tx) { return "TX001"; }
}