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
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\Storage\SchemaStorageLayer;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class AbstractSchemaManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class AbstractSchemaManager  
{
    /**
     * @var SchemaStorageLayer
     */
    protected $storage;
    /**
     * @var string
     */
    protected $operationClass;
    /** 
     * @var ExpressionLanguage
     */
    protected $exp;

    /**
     * @param SchemaStorageLayer $storage
     * @param string $class
     */
    public function __construct(SchemaStorageLayer $storage, $class) {
        $this->storage = $storage;
        $this->operationClass = $class;
        $this->exp = new ExpressionLanguage();
    }

    public function registerFx($name, callable $fx) {
        $this->exp->register($name, $fx, $fx);
        
        return $this;
    }

    /**
     * @param AuthorizationInterface $auth
     * @return OperationInterface[]
     */
    public function getSchemaFor($auth) {
        /** @var OperationInterface[] $op */
        $ops = [];
        $inst = $this->storage->getInstructions($auth->getCode());
        $class = $this->operationClass;
        $proxy = new Proxy($auth);
        $iargs = ["t" => $proxy,];
        foreach($inst as $i) {
            /** @var OperationInterface $op */
            $op = new $class();
            $amount = $this->exp->evaluate($i->getAmount(),$iargs);
            $currency = $this->exp->evaluate($i->getCurrency(),$iargs);
            $walletId = $this->exp->evaluate($i->getWallet(),$iargs);
            $label = $this->exp->evaluate($i->getLabel(),$iargs);
            $direction = $this->exp->evaluate($i->getDirection(),$iargs);

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
