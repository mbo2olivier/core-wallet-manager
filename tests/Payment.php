<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\Operation;

class Payment extends Operation {

    /** @var double */
    public $amount;

    public string $currency;
    public string $walletId;
    public string $requestId;

    /** @var double */
    public $cionsAmount;

    public string $cionsCurrency;

    protected $exchangeRate = "NOR";

    public function __construct(private ?string $code = null)
    {
        
    }

    public function setOperationCode(string $code) {
        $this->code = $code;
    }

    public function getSchemaId(): ?string
    {
        return "SCHM01";
    }

    public function getOperationCode(): ?string
    {
        return $this->code;
    }

    public function getOperationId(): ?string
    {
        return "";
    }

    public function getInstrumentId(): ?string
    {
        return null;
    }

    public function getHolderId(): ?string
    {
        return null;
    }

    public function getPlatformId(): ?string
    {
        return "PF001";
    }

    public function hasDoubleEntrySupport(): bool
    {
        return true;
    }

    public function getDescription(): ?string
    {
        return "fake operation";
    }

    public function getTransactionAmount(): string { return $this->amount; }

    public function getCurrency(): string { return $this->currency; }

    public function getCommissionCurrency(): string { return $this->cionsAmount; }

    public function getCommissionAmount(): string { return $this->cionsCurrency; }

    public function getAuthorizationRequestId(): ?string { return $this->requestId; }

    /**
     * @return null|string
     */
    public function getExchangeRate(): ?string { return $this->exchangeRate; }
    /**
     * @param string $rate
     */
    public function setExchangeRate(?string $rate) { $this->exchangeRate = $rate; }
}