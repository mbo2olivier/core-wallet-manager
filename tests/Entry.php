<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\EntryInterface;

class Entry  implements EntryInterface
{
    /** @var  double */
    protected $amount;
    /** @var  string */
    protected $authorizationId;
    /** @var  double */
    protected $balance;
    /** @var  string */
    protected $currency;

    /** @var  \DateTimeImmutable */
    protected \DateTimeImmutable $date;

    /** @var  ?\DateTimeImmutable null|\DateTimeImmutable */
    protected $executedAt;
    /** @var  string */
    protected $label;
    /** @var  int */
    protected int $serialId;

    /** @var  string */
    protected $platformId;

    /** @var  string */
    protected $type;

    /** @var  string */
    protected $walletId;

    /** @var  double */
    protected $transactionAmount;

    /** @var  string */
    protected $transactionCurrency;

    /** @var  double */
    protected $appliedRate;

    /** @var  string */
    protected $exchangeRate;

    public function __construct(int $id = 1, $amount = 0, $currency = '', $label = '', $type = '', $wallet = '')
    {
        $this->serialId = $id;
        $this->transactionAmount = $amount;
        $this->transactionCurrency = $currency;
        $this->label = $label;
        $this->walletId = $wallet;
        $this->type = $type;
    }
    
    /**
     * @return double
     */
    public function getAmount(): string { return $this->amount; }
    /**
     * @param double $amount
     */
    public function setAmount(string $amount) { $this->amount = $amount; }
    /**
     * @return string
     */
    public function getCurrency(): string { return $this->currency; }
    /**
     * @param string $currency
     */
    public function setCurrency(string $currency) { $this->currency = $currency; }
    /**
     * @return string
     */
    public function getType():string { return $this->type; }
    /**
     * @param string $type
     */
    public function setType(string $type) { $this->type = $type; }
    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable { return $this->date; }
    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date) { $this->date = $date; }
    /**
     * @return string
     */
    public function getWalletId():string { return $this->walletId; }
    /**
     * @param string $id
     */
    public function setWalletId(string $id) { $this->walletId = $id; }

    /**
     * @return string
     */
    public function getLabel(): string { return $this->label; }
    /**
     * @param string $label
     */
    public function setLabel(string $label) { $this->label = $label; }
    /**
     * @return int
     */
    public function getSerialId(): int { return $this->serialId; }
    /**
     * @param string $id
     */
    public function setSerialId(int $id) { $this->serialId = $id; }
    /**
     * @return \DateTimeImmutable
     */
    public function getExecutedAt(): ?\DateTimeImmutable { return $this->executedAt; }
    /**
     * @param \DateTimeImmutable $date
     */
    public function setExecutedAt(?\DateTimeImmutable $date) { $this->executedAt = $date; }
    /**
     * @return string
     */
    public function getAuthorizationId(): string { return $this->authorizationId; }
    /**
     * @param string $authId
     */
    public function setAuthorizationId(string $authId) { $this->authorizationId = $authId; }
    /**
     * @return double
     */
    public function getBalance(): string { return $this->balance; }
    /**
     * @param double $balance
     */
    public function setBalance(string $balance) { $this->balance = $balance; }
    /**
     * @return string
     */
    public function getPlatformId(): string { return $this->platformId; }
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif) { $this->platformId = $pif; }
/**
     * @return double
     */
    public function getTransactionAmount(): string { return $this->transactionAmount; }
    /**
     * @param double $amount
     */
    public function setTransactionAmount(string $amount) { $this->transactionAmount = $amount; }
    /**
     * @return string
     */
    public function getTransactionCurrency():string { return $this->transactionCurrency; } 
    /**
     * @param string $currency
     */
    public function setTransactionCurrency(string $currency) { $this->transactionCurrency = $currency; }
    /**
     * @return null|string
     */
    public function getExchangeRate(): ?string { return $this->exchangeRate; }
    /**
     * @param string $rate
     */
    public function setExchangeRate(?string $rate) { $this->exchangeRate = $rate; }
    /**
     * @return string
     */
    public function getAppliedRate(): ?string { return $this->appliedRate; }
    /**
     * @param string $rate
     */
    public function setAppliedRate(?string $rate) { $this->appliedRate = $rate; }

}