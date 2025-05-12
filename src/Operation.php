<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core;

use DateTimeImmutable;
/**
 * Class Request.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class Operation  
{
    protected ?string $authorizationId;

    public function getAuthorizationId(): string {
        return $this->authorizationId;
    }

    public function setAuthorizationId(string $authorizationId) {
        $this->authorizationId = $authorizationId;
    }

    abstract public function getSchemaId(): ?string;

    abstract public function getOperationId(): ?string;

    abstract public function getOperationCode(): ?string;

    abstract public function getAuthorizationRequestId(): ?string;

    abstract public function getHolderId(): ?string;

    abstract public function getPlatformId(): ?string;

    abstract public function hasDoubleEntrySupport(): bool;

    abstract public function getDescription(): ?string;

    abstract public function getTransactionAmount(): string;

    abstract public function getCurrency(): string;

    abstract public function getCommissionCurrency(): string;

    abstract public function getCommissionAmount(): string;

    abstract public function getExchangeRate(): ?string;
    abstract public function getDate(): ?DateTimeImmutable;
    abstract public function getValueDate(): ?DateTimeImmutable;
}
