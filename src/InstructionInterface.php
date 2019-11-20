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
 * Interface InstructionInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface InstructionInterface
{
    /**
     * @return string
     */
    public function getSchemaId();
    /**
     * @param string $id
     */
    public function setSchemaId($id);
    /**
     * @return string
     */
    public function getWallet();
    /**
     * @param string $wallet
     */
    public function setWallet($wallet);
    /**
     * @return string
     */
    public function getAmount();
    /**
     * @param string $amount
     */
    public function setAmount($amount);
    /**
     * @return string
     */
    public function getCurrency();
    /**
     * @param string $currency
     */
    public function setCurrency($currency);
    /**
     * @return string
     */
    public function getDirection();
    /**
     * @param string $direction
     */
    public function setDirection($direction);
    /**
     * @return string
     */
    public function getLabel();
    /**
     * @param string $label
     */
    public function setLabel($label);
    /**
     * @return integer
     */
    public function getOrder();
    /**
     * @param integer $order
     */
    public function setOrder($order);
}
