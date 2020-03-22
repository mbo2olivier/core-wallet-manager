<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\WalletInterface;

class Wallet  implements WalletInterface
{
    /** @var  string */
    protected $id;
    /** @var  double */
    protected $balance;
    /** @var  \DateTime */
    protected $balanceUpdatedAt;
    /** @var  \DateTime */
    protected $closedAt;
    /** @var  \DateTime */
    protected $createdAt;
    /** @var  string */
    protected $currency;
    /** @var  string */
    protected $holderId;
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $platformId;
    /** @var  string */
    protected $status;
    /** @var  string */
    protected $walletId;
    /** @var  string */
    protected $walletType;
    /** @var  boolean */
    protected $closed;
    /** @var double */
    protected $overdraft;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return \DateTime
     */
    public function getBalanceUpdatedAt()
    {
        return $this->balanceUpdatedAt;
    }

    /**
     * @param \DateTime $balanceUpdatedAt
     */
    public function setBalanceUpdatedAt($balanceUpdatedAt)
    {
        $this->balanceUpdatedAt = $balanceUpdatedAt;
    }

    /**
     * @return boolean
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @param boolean $closed
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;
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
    public function getHolderId()
    {
        return $this->holderId;
    }

    /**
     * @param string $holderId
     */
    public function setHolderId($holderId)
    {
        $this->holderId = $holderId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getWalletType()
    {
        return $this->walletType;
    }

    /**
     * @param string $walletType
     */
    public function setWalletType($walletType)
    {
        $this->walletType = $walletType;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    /** @return double */
    public function getOverdraft()
    {
        return $this->overdraft;
    }

    public function setOverdraft($overdraft)
    {
        $this->overdraft = $overdraft;
    }
}