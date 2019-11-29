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
 * Class Request.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class Request  
{
    /** @var string $code  */
    protected $code;

    /** @var double $amount  */
    protected $amount;

    /** @var string $currency  */
    protected $currency;

    /** @var string $walletId  */
    protected $walletId;

    /** @var string $channelId  */
    protected $channelId;

    /** @var string $authorizationRef  */
    protected $authorizationRef;

    /** @var  string */
    protected $label;

    /** @var  string */
    protected $requester;

    /** @var string $walletId  */
    protected $bufferWalletId;

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
     * Get the value of code
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of amount
     */ 
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @return  self
     */ 
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of currency
     */ 
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @return  self
     */ 
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of walletId
     */ 
    public function getWalletId()
    {
        return $this->walletId;
    }

    /**
     * Set the value of walletId
     *
     * @return  self
     */ 
    public function setWalletId($walletId)
    {
        $this->walletId = $walletId;

        return $this;
    }

    /**
     * Get the value of channelId
     */ 
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set the value of channelId
     *
     * @return  self
     */ 
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get the value of authorizationRef
     */ 
    public function getAuthorizationRef()
    {
        return $this->authorizationRef;
    }

    /**
     * Set the value of authorizationRef
     *
     * @return  self
     */ 
    public function setAuthorizationRef($authorizationRef)
    {
        $this->authorizationRef = $authorizationRef;

        return $this;
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
}
