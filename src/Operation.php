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
abstract class Operation  
{
    protected ?string $authorizationId;

    public function getAuthorizationId(): string {
        return $this->authorizationId;
    }

    public function setAuthorizationId(string $authorizationId) {
        $this->authorizationId = $authorizationId;
    }

    abstract function getSchemaId(): ?string;

    abstract function getOperationId(): ?string;

    abstract function getOperationCode(): ?string;

    abstract function getAuthorizationRequestId(): ?string;

    abstract function getHolderId(): ?string;

    abstract function getPlatformId(): ?string;

    abstract function hasDoubleEntrySupport(): bool;

    abstract function getDescription(): ?string;

    abstract function getTransactionAmount(): string;

    abstract function getCurrency(): string;

    abstract function getCommissionCurrency(): string;

    abstract function getCommissionAmount(): string;

    abstract function getExchangeRate(): ?string;
}
