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
     * @return double
     */
    public function getBalance();
    /**
     * @param double $balance
     */
    public function setBalance($balance);
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
    public function getCode();
    /**
     * @param string $code
     */
    public function setCode($code);
    /**
     * @return string
     */
    public function getType();
    /**
     * @param string $type
     */
    public function setType($type);
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
    public function getBufferWalletId();
    /**
     * @param string $bufferWalletId
     */
    public function setBufferWalletId($bufferWalletId);
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
    public function getRequester();

    /**
     * @param string $requester
     */
    public function setRequester($requester);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
    /**
     * Get the value of data1
     */ 
    public function getData1();
    /**
     * Set the value of data1
     *
     * @return  self
     */ 
    public function setData1($data1);
    /**
     * Get the value of data2
     */ 
    public function getData2();
    /**
     * Set the value of data2
     *
     * @return  self
     */ 
    public function setData2($data2);

    /**
     * Get the value of data3
     */ 
    public function getData3();

    /**
     * Set the value of data3
     *
     * @return  self
     */ 
    public function setData3($data3);
    /**
     * Get the value of data4
     */ 
    public function getData4();

    /**
     * Set the value of data4
     *
     * @return  self
     */ 
    public function setData4($data4);

    /**
     * Get the value of data5
     */ 
    public function getData5();

    /**
     * Set the value of data5
     *
     * @return  self
     */ 
    public function setData5($data5);

    /**
     * Get the value of data6
     */ 
    public function getData6();
    /**
     * Set the value of data6
     *
     * @return  self
     */ 
    public function setData6($data6);
}
