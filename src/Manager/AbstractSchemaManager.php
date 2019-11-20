<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Manager;

use Mukadi\Wallet\Core\Expression\Proxy;
use Mukadi\Wallet\Core\TransactionInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\Storage\SchemaStorageLayer;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class AbstractSchemaManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class AbstractSchemaManager  
{
    /**
     * @var SchemaStorageLayer
     */
    protected $storage;
    /**
     * @var string
     */
    protected $operatonClass;

    /**
     * @param SchemaStorageLayer $storage
     * @param string $class
     */
    public function __construct(SchemaStorageLayer $storage, $class) {
        $this->storage = $storage;
        $this->operatonClass = $class;
    }

    /**
     * @param string $code
     * @return OperationInterface[]
     */
    public function getSchemaFor($code) {
        /** @var OperationInterface[] $op */
        $ops = [];
        $inst = $this->storage->getInstructions($code);
        $class = $this->operatonClass;
        $exp = new ExpressionLanguage();
        foreach($inst as $i) {
            /** @var OperationInterface $op */
            $op = new $class();
            $proxy = new Proxy($i);
            $amount = $exp->evaluate($i->getAmount(),["t" => $proxy,]);
            $currency = $exp->evaluate($i->getCurrency(),["t" => $proxy,]);
            $walletId = $exp->evaluate($i->getWallet(),["t" => $proxy,]);
            $label = $exp->evaluate($i->getLabel(),["t" => $proxy,]);
            $direction = $exp->evaluate($i->getDirection(),["t" => $proxy,]);

            $op->setAmount($amount);
            $op->setCurrency($currency);
            $op->setWalletId($walletId);
            $op->setLabel($label);
            $op->setType($direction);

            $ops[] = $op;
        }
        return $ops;
    }
}