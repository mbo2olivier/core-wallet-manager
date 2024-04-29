<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2024 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core;

class Lien {

    /** @var  string */
    protected ?string $authorizationId = null;

    /** @var  string */
    protected ?string $platformId = null;

    /** @var  int */
    protected ?int $serialId = 0;
    protected ?string $reason = null;

    /** @var  double */
    protected ?string $amount = null;

    /** @var  double */
    protected ?string $originalAmount = null;

    protected ?string $walletId = null;

    /** @var  ?\DateTimeImmutable null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $createdAt = null;

    /** @var  ?\DateTimeImmutable null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $closedAt = null;
    protected ?\DateTimeImmutable $activatedAt = null;

    protected ?string $operationCode = null;
    protected ?string $operationId = null;
    protected ?string $activatedBy = null;
    protected ?string $createdBy = null;
    protected ?string $status = null;

    private function __construct() {
        $this->status  = Codes::LIEN_STATUS_ACTIVE;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * Get the value of reason
     */ 
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * Set the value of reason
     *
     * @return  self
     */ 
    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get the value of wallet
     */ 
    public function getWalletId(): string
    {
        return $this->walletId;
    }

    /**
     * Set the value of wallet
     *
     * @return  self
     */ 
    public function setWalletId(string $walletId): self
    {
        $this->walletId = $walletId;

        return $this;
    }

        /**
     * @return double
     */
    public function getAmount(): string { return $this->amount; }
    /**
     * @param double $amount
     */
    public function setAmount(string $amount): self 
    {
        $this->amount = $amount;
        return $this;
    }

        /**
     * @return double
     */
    public function getOriginalAmount(): string { return $this->originalAmount; }
    /**
     * @param double $amount
     */
    public function setOriginalAmount(string $amount): self 
    {
        $this->originalAmount = $amount; 
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    /**
     * @param \DateTimeImmutable $date
     */
    public function setCreatedAt(?\DateTimeImmutable $date): self {
        $this->createdAt = $date;
        return $this;
    }

    /**
     * Get the value of operationCode
     */ 
    public function getOperationCode(): ?string
    {
        return $this->operationCode;
    }

    /**
     * Set the value of operationCode
     *
     * @return  self
     */ 
    public function setOperationCode(?string $operationCode): self
    {
        $this->operationCode = $operationCode;

        return $this;
    }

    /**
     * Get the value of operationId
     */ 
    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    /**
     * Set the value of operationId
     *
     * @return  self
     */ 
    public function setOperationId(?string $operationId): self
    {
        $this->operationId = $operationId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSerialId(): int { return $this->serialId; }
    /**
     * @param string $id
     */
    public function setSerialId(?int $id): self 
    {
        $this->serialId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorizationId(): ?string { return $this->authorizationId; }
    /**
     * @param string $authId
     */
    public function setAuthorizationId(?string $authId): self 
    { 
        $this->authorizationId = $authId; 
        return $this;
    }

    /**
     * Get the value of closededAt
     */ 
    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    /**
     * Set the value of closededAt
     *
     * @return  self
     */ 
    public function setClosedAt(?\DateTimeImmutable $closedAt): self
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    

    /**
     * Get the value of activatedAt
     */ 
    public function getActivatedAt(): ?\DateTimeImmutable
    {
        return $this->activatedAt;
    }

    /**
     * Set the value of activatedAt
     *
     * @return  self
     */ 
    public function setActivatedAt(?\DateTimeImmutable $activatedAt): self
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get the value of activatedBy
     */ 
    public function getActivatedBy(): ?string
    {
        return $this->activatedBy;
    }

    /**
     * Set the value of activatedBy
     *
     * @return  self
     */ 
    public function setActivatedBy(?string $activatedBy): self
    {
        $this->activatedBy = $activatedBy;

        return $this;
    }

    /**
     * Get the value of createdBy
     */ 
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of platformId
     */ 
    public function getPlatformId(): string
    {
        return $this->platformId;
    }

    /**
     * Set the value of platformId
     *
     * @return  self
     */ 
    public function setPlatformId(string $platformId): self
    {
        $this->platformId = $platformId;

        return $this;
    }

    public static function createNewInstance(string $walletId, string $amount, string $reason, ?string $operationCode = null, ?string $operationId = null): static {
        $lien = new self;
        $lien->setWalletId($walletId);
        $lien->setOriginalAmount($amount);
        $lien->setAmount($amount);
        $lien->setReason($reason);
        $lien->setOperationCode($operationCode);
        $lien->setOperationId($operationId);

        return $lien;
    }
}