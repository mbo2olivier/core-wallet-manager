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
 * Interface TransactionInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface TransactionInterface
{
    /**
     * @return string
     */
    public function getToken();
    /**
     * @param string $token
     */
    public function setToken($token);
    /**
     * @return string
     */
    public function getTransactionId();
    /**
     * @param string $id
     */
    public function setTransactionId($id);
    /**
     * @return \DateTime
     */
    public function getBeginAt();
    /**
     * @param \DateTime $date
     */
    public function setBeginAt($date);
    /**
     * @return \DateTime
     */
    public function getEndedAt();
    /**
     * @param \DateTime $date
     */
    public function setEndedAt($date);
    /**
     * @return double
     */
    public function getAmount();
    /**
     * @param double $amount
     */
    public function setAmount($amount);
    /**
     * @return string
     */
    public function getCurrency();
    /**
     * @param string $currency
     */
    public function setCurrency($currency);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
    /**
     * @return string
     */
    public function getChannel();
    /**
     * @param string $channel
     */
    public function setChannel($channel);
    /**
     * @return string
     */
    public function getAuthorizationId();
    /**
     * @param string $authId
     */
    public function setAuthorizationId($authId);
    /**
     * @return string
     */
    public function getCode();
    /**
     * @param string $code
     */
    public function setCode($code);
    /**
     * @return string
     */
    public function getInitiator();
    /**
     * @param string $initiator
     */
    public function setInitiator($initiator);
    /**
     * @return string
     */
    public function getTarget();
    /**
     * @param string $target
     */
    public function setTarget($target);
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
}
