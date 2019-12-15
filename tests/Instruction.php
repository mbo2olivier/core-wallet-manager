<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\InstructionInterface;

class Instruction  implements InstructionInterface
{
    /** @var  integer */
    protected $id;
    /** @var  string */
    protected $amount;
    /** @var  string */
    protected $currency;
    /** @var  string */
    protected $direction;
    /** @var  string */
    protected $label;
    /** @var  integer */
    protected $order;
    /** @var  string */
    protected $schemaId;
    /** @var  string */
    protected $wallet;

    public function __construct($amount = '', $currency = '', $direction = '', $label = '', $order = 0, $schemaId ='', $wallet ='') {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->direction = $direction;
        $this->label = $label;
        $this->order = $order;
        $this->schemaId = $schemaId;
        $this->wallet = $wallet;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSchemaId()
    {
        return $this->schemaId;
    }

    /**
     * @param string $schemaId
     */
    public function setSchemaId($schemaId)
    {
        $this->schemaId = $schemaId;
    }

    /**
     * @return string
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @param string $wallet
     */
    public function setWallet($wallet)
    {
        $this->wallet = $wallet;
    }

}