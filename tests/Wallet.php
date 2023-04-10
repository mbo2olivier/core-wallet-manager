<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\WalletInterface;

class Wallet  implements WalletInterface
{

    /** @var  double */
    protected $balance;

    /** @var  null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $balanceUpdatedAt;

    /** @var  null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $closedAt;

    /** @var  \DateTimeImmutable */
    protected \DateTimeImmutable $createdAt;

    /** @var  string */
    protected string $currency;

    /** @var  null|string */
    protected ?string $holderId;

    /** @var  null|string */
    protected ?string $name;

    /** @var  string */
    protected string $platformId;

    /** @var  null|string */
    protected ?string $glCode;

    /** @var  string */
    protected string $walletId;

    /** @var  null|string */
    protected ?string $walletPublicId;

    /** @var  null|string */
    protected ?string $walletProfileId;

    /** @var  null|string */
    protected ?string $walletTypeId;

    /** @var  boolean */
    protected bool $closed;


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
    public function getCurrency(): string { return $this->currency; }
    /**
     * @param string $currency
     */
    public function setCurrency(string $currency) { $this->currency = $currency; }
    /**
     * @return string
     */
    public function getName(): ?string { return $this->name; }
    /**
     * @param string $name
     */
    public function setName(?string $name) { $this->name = $name; }
    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    /**
     * @param \DateTimeImmutable $date
     */
    public function setCreatedAt(\DateTimeImmutable $date) { $this->createdAt = $date; }
    /**
     * @return ?\DateTimeImmutable
     */
    public function getBalanceUpdatedAt(): ?\DateTimeImmutable { return $this->balanceUpdatedAt; }
    /**
     * @param ?\DateTimeImmutable $date
     */
    public function setBalanceUpdatedAt(?\DateTimeImmutable $date) { $this->balanceUpdatedAt = $date; }
    /**
     * @return string
     */
    public function getWalletProfileId():?string { return $this->walletProfileId; }
    /**
     * @param string $type
     */
    public function setWalletProfileId(?string $profileId) { $this->walletProfileId = $profileId; }
    /**
     * @return string
     */
    public function getHolderId():?string { return $this->holderId; }
    /**
     * @param string $holder
     */
    public function setHolderId(?string $holderId) { $this->holderId = $holderId; }
    /**
     * @return string
     */
    public function getWalletId(): string { return $this->walletId; }
    /**
     * @param string $id
     */
    public function setWalletId(string $id) { $this->walletId = $id; }

    /**
     * @return string
     */
    public function getWalletPublicId(): ?string { return $this->walletPublicId; }
    /**
     * @param string $id
     */
    public function setWalletPublicId(?string $id) { $this->walletPublicId = $id; }

    /**
     * @return boolean
     */
    public function isClosed(): bool { return $this->closed; }
    /**
     * @param boolean $closed
     */
    public function setClosed(bool $closed) { $this->closed = $closed; }
    /**
     * @return ?\DateTimeImmutable
     */
    public function getClosedAt(): ?\DateTimeImmutable { return $this->closedAt; }
    /**
     * @param ?\DateTimeImmutable $date
     */
    public function setClosedAt(?\DateTimeImmutable $date) { $this->closedAt = $date; }
    /**
     * @return string
     */
    public function getPlatformId(): string { return $this->platformId; }
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif) { $this->platformId = $pif; }
    /**
     * @return string
     */
    public function getGlCode(): ?string { return $this->glCode; }
    /**
     * @param double $code
     */
    public function setGlCode(?string $code) { $this->glCode = $code; }
    /**
     * @return string
     */
    public function getWalletTypeId():?string { return $this->walletTypeId; }
    /**
     * @param string $type
     */
    public function setWalletTypeId(?string $typeId) { $this->walletTypeId = $typeId; }
}