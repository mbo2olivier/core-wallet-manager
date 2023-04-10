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
 * Class HolderInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface HolderInterface {

    /**
     * @return string
     */
    public function getHolderId(): string;
    /**
     * @param string $holder
     */
    public function setHolderId(string $holder);
    /**
     * @return string
     */
    public function getFirstName(): ?string;
    /**
     * @param string $name
     */
    public function setFirstName(?string $name);
    /**
     * @return string
     */
    public function getLastName(): ?string;
    /**
     * @param string $name
     */
    public function setLastName(?string $name);
    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable;
    /**
     * @param \DateTDateTimeImmutableime $date
     */
    public function setCreatedAt(\DateTimeImmutable $date);
    /**
     * @return ?\DateTimeImmutable
     */
    public function getUpdatedAt(): ?\DateTimeImmutable;
    /**
     * @param ?\DateTime $date
     */
    public function setUpdatedAt(?\DateTimeImmutable $date);
    /**
     * @return string
     */
    public function getAddress(): ?string;
    /**
     * @param string $address
     */
    public function setAddress(?string $address);
    /**
     * @return string
     */
    public function getCountry(): ?string;
    /**
     * @param string $country
     */
    public function setCountry(?string $country);
    /**
     * @return string
     */
    public function getState(): ?string;
    /**
     * @param string $state
     */
    public function setState(?string $state);
    /**
     * @return string
     */
    public function getPhone(): ?string;
    /**
     * @param string $phone
     */
    public function setPhone(?string $phone);
    /**
     * @return string
     */
    public function getPhone2(): ?string;
    /**
     * @param string $phone
     */
    public function setPhone2(?string $phone);
    /**
     * @return string
     */
    public function getRegistrationDoc(): ?string;
    /**
     * @param string $doc
     */
    public function setRegistrationDoc(?string $doc);
    /**
     * @return string
     */
    public function getRegistrationDocId(): ?string;
    /**
     * @param string $id
     */
    public function setRegistrationDocId(?string $id);
    /**
     * @return string
     */
    public function getProfilId(): ?string;
    /**
     * @param string $id
     */
    public function setProfilId(?string $id);
    /**
     * @return string
     */
    public function getEmail(): ?string;
    /**
     * @param string $email
     */
    public function setEmail(?string $email);
    /**
     * @return string
     */
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);
}