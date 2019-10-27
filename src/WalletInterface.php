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
 * Interface WalletInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface WalletInterface
{
    /**
     * @return decimal
     */
    public function getBalance();
    /**
     * @param decimal $balance
     */
    public function setBalance($balance);
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
    public function getName();
    /**
     * @param string $name
     */
    public function setName($name);
    /**
     * @return \DateTime
     */
    public function getCreatedAt();
    /**
     * @param \DateTime $date
     */
    public function setCreatedAt($date);
    /**
     * @return \DateTime
     */
    public function getBalancepdatedAt();
    /**
     * @param \DateTime $date
     */
    public function setBalancepdatedAt($date);
    /**
     * @return string
     */
    public function getWalletType();
    /**
     * @param string $type
     */
    public function setWalletType($type);
    /**
     * @return string
     */
    public function getHolderId();
    /**
     * @param string $holder
     */
    public function setHolderId($holder);
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
    public function getStatus();
    /**
     * @param string $status
     */
    public function setStatus($status);
    /**
     * @return boolean
     */
    public function isClosed();
    /**
     * @param boolean $closed
     */
    public function setClosed($closed);
    /**
     * @return \DateTime
     */
    public function getClosedAt();
    /**
     * @param \DateTime $date
     */
    public function setClosedAt($date);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
}
