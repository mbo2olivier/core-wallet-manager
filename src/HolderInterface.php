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
    public function getHolderId();
    /**
     * @param string $holder
     */
    public function setHolderId($holder);
    /**
     * @return string
     */
    public function getFirstName();
    /**
     * @param string $name
     */
    public function setFirstName($name);
    /**
     * @return string
     */
    public function getLastName();
    /**
     * @param string $name
     */
    public function setLastName($name);
    /**
     * @return \DateTime
     */
    public function getCreatedAt();
    /**
     * @param \DateTime $date
     */
    public function setCreatedAt($date);
    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
    /**
     * @param \DateTime $date
     */
    public function setUpdatedAt($date);
    /**
     * @return string
     */
    public function getAddress();
    /**
     * @param string $address
     */
    public function setAddress($address);
    /**
     * @return string
     */
    public function getCountry();
    /**
     * @param string $country
     */
    public function setCountry($country);
    /**
     * @return string
     */
    public function getState();
    /**
     * @param string $state
     */
    public function setState($state);
    /**
     * @return string
     */
    public function getPhone();
    /**
     * @param string $phone
     */
    public function setPhone($phone);
    /**
     * @return string
     */
    public function getPhone2();
    /**
     * @param string $phone
     */
    public function setPhone2($phone);
    /**
     * @return string
     */
    public function getRegistrationDoc();
    /**
     * @param string $doc
     */
    public function setRegistrationDoc($doc);
    /**
     * @return string
     */
    public function getRegistrationDocId();
    /**
     * @param string $id
     */
    public function setRegistrationDocId($id);
    /**
     * @return string
     */
    public function getProfilId();
    /**
     * @param string $id
     */
    public function setProfilId($id);
    /**
     * @return string
     */
    public function getEmail();
    /**
     * @param string $email
     */
    public function setEmail($email);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
}