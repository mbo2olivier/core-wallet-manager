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
 * Interface Operation.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface OperationInterface
{
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
    public function getType();
    /**
     * @param string $type
     */
    public function setType($type);
    /**
     * @return \DateTime
     */
    public function getDate();
    /**
     * @param \DateTime $date
     */
    public function setDate($date);
    /**
     * @return string
     */
    public function getWalletId();
    /**
     * @param string $id
     */
    public function setWalletId($id);
    /**
     * @return string
     */
    public function getMaker();
    /**
     * @param string $maker
     */
    public function setMaker($maker);
    /**
     * @return string
     */
    public function getValidator();
    /**
     * @param string $validator
     */
    public function setValidator($validator);
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
    public function getLabel();
    /**
     * @param string $label
     */
    public function setLabel($label);
    /**
     * @return string
     */
    public function getOperationId();
    /**
     * @param string $id
     */
    public function setOperationId($id);
    /**
     * @return \DateTime
     */
    public function getValidatedAt();
    /**
     * @param \DateTime $date
     */
    public function setValidatedAt($date);
    /**
     * @return \DateTime
     */
    public function getExecutedAt();
    /**
     * @param \DateTime $date
     */
    public function setExecutedAt($date);
    /**
     * @return string
     */
    public function getAuthorizationId();
    /**
     * @param string $authId
     */
    public function setAuthorizationId($authId);
    /**
     * @return double
     */
    public function getBalance();
    /**
     * @param double $balance
     */
    public function setBalance($balance);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
    /**
     * @return bool
     */
    public function isReversal();
    /**
     * @param boolean $b
     */
    public function setReversal($b);
    /**
     * @return string
     */
    public function getReversedFrom();
    /**
     * @param string $opId
     */
    public function setReversedFrom($opId);
}
