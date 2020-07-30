<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\AuthorizationInterface;

class Authorization  implements AuthorizationInterface
{
    /** @var  integer */
    protected $id;
    /** @var  double */
    protected $amount;
    /** @var  string */
    protected $authorizationId;
    /** @var  string */
    protected $authorizationRef;
    /** @var  double */
    protected $balance;
    /** @var  string */
    protected $channelId;
    /** @var  string */
    protected $code;
    /** @var  string */
    protected $currency;
    /** @var  string */
    protected $platformId;
    /** @var  string */
    protected $requester;
    /** @var  string */
    protected $status;
    /** @var  string */
    protected $type;
    /** @var  string */
    protected $walletId;
    /** @var  string */
    protected $bufferWalletId;

    /** @var  string */
    protected $data1;

    /** @var  string */
    protected $data2;

    /** @var  string */
    protected $data3;

    /** @var  string */
    protected $data4;

    /** @var  string */
    protected $data5;

    /** @var  string */
    protected $data6;

    /** @var  string */
    protected $label;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getRequester()
    {
        return $this->requester;
    }

    /**
     * @param string $requester
     */
    public function setRequester($requester)
    {
        $this->requester = $requester;
    }

    /**
     * @return string
     */
    public function getAuthorizationId()
    {
        return $this->authorizationId;
    }

    /**
     * @param string $authorizationId
     */
    public function setAuthorizationId($authorizationId)
    {
        $this->authorizationId = $authorizationId;
    }

    /**
     * @return string
     */
    public function getAuthorizationRef()
    {
        return $this->authorizationRef;
    }

    /**
     * @param string $authorizationRef
     */
    public function setAuthorizationRef($authorizationRef)
    {
        $this->authorizationRef = $authorizationRef;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return string
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @param string $channelId
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getPlatformId()
    {
        return $this->platformId;
    }

    /**
     * @param string $platformId
     */
    public function setPlatformId($platformId)
    {
        $this->platformId = $platformId;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getWalletId()
    {
        return $this->walletId;
    }

    /**
     * @param string $walletId
     */
    public function setWalletId($walletId)
    {
        $this->walletId = $walletId;
    }

    /**
     * @return string
     */
    public function getBufferWalletId()
    {
        return $this->bufferWalletId;
    }

    /**
     * @param string $bufferWalletId
     */
    public function setBufferWalletId($bufferWalletId)
    {
        $this->bufferWalletId = $bufferWalletId;
    }

    /**
     * Get the value of data1
     */ 
    public function getData1()
    {
        return $this->data1;
    }

    /**
     * Set the value of data1
     *
     * @return  self
     */ 
    public function setData1($data1)
    {
        $this->data1 = $data1;

        return $this;
    }

    /**
     * Get the value of data2
     */ 
    public function getData2()
    {
        return $this->data2;
    }

    /**
     * Set the value of data2
     *
     * @return  self
     */ 
    public function setData2($data2)
    {
        $this->data2 = $data2;

        return $this;
    }

    /**
     * Get the value of data3
     */ 
    public function getData3()
    {
        return $this->data3;
    }

    /**
     * Set the value of data3
     *
     * @return  self
     */ 
    public function setData3($data3)
    {
        $this->data3 = $data3;

        return $this;
    }

    /**
     * Get the value of data4
     */ 
    public function getData4()
    {
        return $this->data4;
    }

    /**
     * Set the value of data4
     *
     * @return  self
     */ 
    public function setData4($data4)
    {
        $this->data4 = $data4;

        return $this;
    }

    /**
     * Get the value of data5
     */ 
    public function getData5()
    {
        return $this->data5;
    }

    /**
     * Set the value of data5
     *
     * @return  self
     */ 
    public function setData5($data5)
    {
        $this->data5 = $data5;

        return $this;
    }

    /**
     * Get the value of data6
     */ 
    public function getData6()
    {
        return $this->data6;
    }

    /**
     * Set the value of data6
     *
     * @return  self
     */ 
    public function setData6($data6)
    {
        $this->data6 = $data6;

        return $this;
    }
}