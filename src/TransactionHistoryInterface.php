<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core;
/**
 * Interface TransactionHistoryInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface TransactionHistoryInterface
{
    /**
     * @return string
     */
    public function getTransactionId();
    /**
     * @param string $id
     */
    public function setTransactionId($id);
    /**
     * @return string
     */
    public function getStatus();
    /**
     * @param string $status
     */
    public function setStatus($status);
    /**
     * @return string
     */
    public function getNote();
    /**
     * @param string $note
     */
    public function setNote($note);
    /**
     * @return \DateTime
     */
    public function getDate();
    /**
     * @param \DateTime $date
     */
    public function setDate($date);
}
