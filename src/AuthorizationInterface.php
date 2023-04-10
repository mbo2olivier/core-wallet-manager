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
 * Interface AuthorizationInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface AuthorizationInterface
{
    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable;
    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date);

    /**
     * @return string|null
     */
    public function getDescription(): ?string;
    /**
     * @param string|null $descr
     */
    public function setDescription(?string $descr);

    /**
     * @return string|null
     */
    public function getOperationCode(): ?string;
    /**
     * @param string|null $code
     */
    public function setOperationCode(?string $code);
    /**
     * @return string|null
     */
    public function getOperationId(): ?string;
    /**
     * @param string|null $id
     */
    public function setOperationId(?string $id);
    /**
     * @return string
     */
    public function getAuthorizationId(): string;
    /**
     * @param string $id
     */
    public function setAuthorizationId(string $id);
    /**
     * @return string
     */
    public function getStatus(): string;
    /**
     * @param string $status
     */
    public function setStatus(string $status);
    /**
     * @return string|null
     */
    public function getAuthorizationRequestId(): ?string;
    /**
     * @param string $id
     */
    public function setAuthorizationRequestId(string $requestId);
    /**
     * @return string|null
     */
    public function getHolderId(): ?string;

    /**
     * @param string|null $holderId
     */
    public function setHolderId(?string $holderId);
    /**
     * @return string|null
     */
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);

    public function getEncodedBy(): ?string;

    public function setEncodedBy(?string $user);

    public function getValidatededBy(): ?string;

    public function setValidatedBy(?string $user);

    public function getEncodedAt(): ?\DateTimeImmutable;

    public function setEncodedAt(?\DateTimeImmutable $date);

    public function getValidatedAt(): ?\DateTimeImmutable;

    public function setValidatedAt(?\DateTimeImmutable $date);

    public function getSchemaId(): string;

    public function setSchemaId(string $schemaId);

    public function getTransactionAmount(): string;

    public function setTransactionAmount(string $amount);

    public function getCurrency(): string;

    public function setCurrency(string $currency);

    public function getCommissionAmount(): string;

    public function setCommissionAmount(string $amount);

    public function getCommissionCurrency(): string;

    public function setCommissionCurrency(string $currency);
}
