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
 * Interface Instruction.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class Instruction
{
    const AMOUNT_COMPUTED       = 0b00000001;
    const CURRENCY_COMPUTED     = 0b00000010;
    const DIRECTION_COMPUTED    = 0b00000100;
    const LABEL_COMPUTED        = 0b00001000;
    const WALLET_COMPUTED       = 0b00010000;

    /** @var  integer */
    protected int $computability;
    /** @var  string */
    protected string $amount;
    /** @var  string */
    protected string $currency;
    /** @var  string */
    protected string $direction;
    /** @var  string */
    protected string $label;
    /** @var  integer */
    protected int $order;
    /** @var  string */
    protected string $schemaId;
    /** @var  string */
    protected string $wallet;

    public function __construct(int $order = 0, $amount = '', $currency = '', $direction = '', $label = '', $schemaId ='', $wallet ='', int $computability = 0) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->direction = $direction;
        $this->label = $label;
        $this->order = $order;
        $this->schemaId = $schemaId;
        $this->wallet = $wallet;
        $this->computability = $computability;
    }
    
    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSchemaId(): string
    {
        return $this->schemaId;
    }

    /**
     * @param string $schemaId
     */
    public function setSchemaId(string $schemaId)
    {
        $this->schemaId = $schemaId;
    }

    /**
     * @return string
     */
    public function getWallet(): string
    {
        return $this->wallet;
    }

    /**
     * @param string $wallet
     */
    public function setWallet(string $wallet)
    {
        $this->wallet = $wallet;
    }

    public function is(int $field): bool {
        return $this->computability & $field;
    }

    public function set(int $field) {
        $this->computability |= $field;
    }
}
