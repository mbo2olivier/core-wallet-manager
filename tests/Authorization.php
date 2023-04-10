<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\AuthorizationInterface;

class Authorization  implements AuthorizationInterface
{
    /** @var  \DateTimeImmutable */
    protected \DateTimeImmutable $date;

    /** @var  string */
    protected null|string $description;

    /** @var  string */
    protected string $authorizationId;

    /** @var  string */
    protected null|string $operationId;

    /** @var  string */
    protected ?string $operationCode;

    /** @var  string */
    protected string $status;

    /** @var  null|string */
    protected ?string $instrumentId;

    /** @var  null|string */
    protected ?string $holderId;

    /** @var  string */
    protected ?string $platformId;

    /** @var  null|string */
    protected ?string $encoder;

    /** @var  null|string */
    protected ?string $validator;

    /** @var  null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $encodedAt;

    /** @var  null|\DateTimeImmutable */
    protected ?\DateTimeImmutable $validatedAt;

    /** @var  string */
    protected string $schemaId;

    /** @var  double */
    protected $transactionAmount;

    /** @var  string */
    protected $currency;

    /** @var  double */
    protected $commissionAmount;

    /** @var  string */
    protected string $commissionCurrency;

    /** @var  string */
    protected string $authorizationRequestId;

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable { return $this->date; }
    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date) { $this->date = $date; }

    /**
     * @return string|null
     */
    public function getDescription(): ?string { return $this->description; }
    /**
     * @param string|null $descr
     */
    public function setDescription(?string $descr) { $this->description = $descr; }

    /**
     * @return string|null
     */
    public function getOperationCode(): ?string { return $this->operationCode; }
    /**
     * @param string|null $code
     */
    public function setOperationCode(?string $code) { $this->operationCode = $code; }
    /**
     * @return string|null
     */
    public function getOperationId(): ?string { return $this->operationId; }
    /**
     * @param string|null $id
     */
    public function setOperationId(?string $id) { $this->operationId = $id; }
    /**
     * @return string
     */
    public function getAuthorizationId(): string { return $this->authorizationId; }
    /**
     * @param string $id
     */
    public function setAuthorizationId(string $id) { $this->authorizationId = $id; }
    /**
     * @return string
     */
    public function getStatus(): string { return $this->status; }
    /**
     * @param string $status
     */
    public function setStatus(string $status) { $this->status = $status; }

    /**
     * @return string|null
     */
    public function getHolderId(): ?string { return $this->holderId; }

    /**
     * @param string|null $holderId
     */
    public function setHolderId(?string $holderId) { $this->holderId = $holderId; }
    /**
     * @return string|null
     */
    public function getPlatformId(): string { return $this->platformId; }
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif) { $this->platformId = $pif; }

    public function getEncodedBy(): ?string { return $this->encoder; }

    public function setEncodedBy(?string $user) { $this->encoder = $user; }

    public function getValidatededBy(): ?string { return $this->validator; }

    public function setValidatedBy(?string $user) { $this->validator = $user; }

    public function getEncodedAt(): ?\DateTimeImmutable { return $this->encodedAt; }

    public function setEncodedAt(?\DateTimeImmutable $date) { $this->encodedAt = $date; }

    public function getValidatedAt(): ?\DateTimeImmutable { return $this->validatedAt; }

    public function setValidatedAt(?\DateTimeImmutable $date) { $this->validatedAt = $date; }

    public function getSchemaId(): string { return $this->schemaId; }

    public function setSchemaId(string $id) { $this->schemaId = $id; }

    public function getTransactionAmount(): string { return $this->transactionAmount; }

    public function setTransactionAmount(string $amount) { $this->transactionAmount = $amount; }

    public function getCurrency(): string { return $this->currency; }

    public function setCurrency(string $currency) { $this->currency = $currency; }

    public function getCommissionAmount(): string { return $this->commissionAmount; }

    public function setCommissionAmount(string $amount) { $this->commissionAmount = $amount; }

    public function getCommissionCurrency(): string { return $this->commissionCurrency; }

    public function setCommissionCurrency(string $currency) { $this->commissionCurrency = $currency; }

    /**
     * Get the value of authorizationRequestId
     */ 
    public function getAuthorizationRequestId(): string
    {
        return $this->authorizationRequestId;
    }

    /**
     * Set the value of authorizationRequestId
     *
     * @return  self
     */ 
    public function setAuthorizationRequestId(string $authorizationRequestId)
    {
        $this->authorizationRequestId = $authorizationRequestId;

        return $this;
    }
}