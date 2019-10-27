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
 * Interface AuthorizationInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface AuthorizationInterface
{
    /**
     * @return decimal
     */
    public function getAmount();
    /**
     * @param decimal $amount
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
    public function getCode();
    /**
     * @param string $code
     */
    public function setCode($code);
    /**
     * @return string
     */
    public function getAuthorizationId();
    /**
     * @param string $id
     */
    public function setAuthorizationId($id);
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
    public function getWalletId();
    /**
     * @param string $id
     */
    public function setWalletId($id);
    /**
     * @return string
     */
    public function getChannelId();
    /**
     * @param string $id
     */
    public function setChannelId($id);
    /**
     * @return string
     */
    public function getAuthorizationRef();
    /**
     * @param string $ref
     */
    public function setAuthorizationRef($ref);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
}
