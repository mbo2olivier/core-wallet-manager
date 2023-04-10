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
 * Interface Entry.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface EntryInterface
{
    /**
     * @return double
     */
    public function getAmount(): string;
    /**
     * @param double $amount
     */
    public function setAmount(string $amount);
    /**
     * @return string
     */
    public function getCurrency();
    /**
     * @param string $currency
     */
    public function setCurrency(string $currency);
    /**
     * @return string
     */
    public function getType():string;
    /**
     * @param string $type
     */
    public function setType(string $type);
    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable;
    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date);
    /**
     * @return string
     */
    public function getWalletId():string;
    /**
     * @param string $id
     */
    public function setWalletId(string $id);

    /**
     * @return string
     */
    public function getLabel(): string;
    /**
     * @param string $label
     */
    public function setLabel(string $label);
    /**
     * @return int
     */
    public function getSerialId(): int;
    /**
     * @param string $id
     */
    public function setSerialId(int $id);
    /**
     * @return \DateTimeImmutable
     */
    public function getExecutedAt(): ?\DateTimeImmutable;
    /**
     * @param \DateTimeImmutable $date
     */
    public function setExecutedAt(?\DateTimeImmutable $date);
    /**
     * @return string
     */
    public function getAuthorizationId(): string;
    /**
     * @param string $authId
     */
    public function setAuthorizationId(string $authId);
    /**
     * @return double
     */
    public function getBalance(): string;
    /**
     * @param double $balance
     */
    public function setBalance(string $balance);
    /**
     * @return string
     */
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);
    /**
     * @return double
     */
    public function getTransactionAmount(): string;
    /**
     * @param double $amount
     */
    public function setTransactionAmount(string $amount);
    /**
     * @return string
     */
    public function getTransactionCurrency(): string;
    /**
     * @param string $currency
     */
    public function setTransactionCurrency(string $currency);
    /**
     * @return null|string
     */
    public function getExchangeRate(): ?string;
    /**
     * @param string $rate
     */
    public function setExchangeRate(?string $rate);
    /**
     * @return null|string
     */
    public function getAppliedRate():?string;
    /**
     * @param string $rate
     */
    public function setAppliedRate(string $rate);
}
