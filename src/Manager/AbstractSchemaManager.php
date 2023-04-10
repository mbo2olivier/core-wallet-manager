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
use Mukadi\Wallet\Core\EntryInterface;
use Mukadi\Wallet\Core\Exception\SchemaException;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Instruction;
use Mukadi\Wallet\Core\Operation;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class AbstractSchemaManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class AbstractSchemaManager  
{
    /**
     * @var WalletStorageLayer
     */
    protected $storage;
    /**
     * @var string
     */
    protected $entryClass;
    /** 
     * @var ExpressionLanguage
     */
    protected $exp;

    /**
     * @param WalletStorageLayer $storage
     * @param string $class
     */
    public function __construct(WalletStorageLayer $storage, $class) {
        $this->storage = $storage;
        $this->entryClass = $class;
        $this->exp = new ExpressionLanguage();
        $this->registerUtilityFunctions();
    }

    public function registerFx($name, callable $fx) {
        $this->exp->register($name, fn () => "", $fx);
        
        return $this;
    }

    /**
     * @param Operation $operation
     * @return EntryInterface[]
     */
    public function getSchemaFor(Operation $operation): iterable {
        $schema = $this->storage->findSchemaBy(['id' => $operation->getSchemaId()]);
        if (null === $schema) {
            throw new SchemaException(sprintf('cannot find schema with ID: %s', $operation->getSchemaId()));
        }
        
         /** @var EntryInterface[] $ops */
        $ops = [];
        $inst = $this->storage->getInstructions($operation->getSchemaId());
        $class = $this->entryClass;
        $proxy = new Proxy($operation);
        $iargs = ["t" => $proxy,];
        $serial = 1;
        foreach($inst as $i) {
            /** @var EntryInterface $op */
            $op = new $class();
            $amount = $i->is(Instruction::AMOUNT_COMPUTED) ? $this->exp->evaluate($i->getAmount(),$iargs): doubleval($i->getAmount());
            $currency = $i->is(Instruction::CURRENCY_COMPUTED) ? $this->exp->evaluate($i->getCurrency(),$iargs): $i->getCurrency();
            $walletId = $i->is(Instruction::WALLET_COMPUTED) ? $this->exp->evaluate($i->getWallet(),$iargs): $i->getWallet();
            $label = $i->is(Instruction::LABEL_COMPUTED) ?  $this->exp->evaluate($i->getLabel(),$iargs): $i->getLabel();
            $direction = $i->is(Instruction::DIRECTION_COMPUTED) ? $this->exp->evaluate($i->getDirection(),$iargs): $i->getDirection();

            $op->setTransactionAmount($amount);
            $op->setTransactionCurrency($currency);
            $op->setWalletId($walletId);
            $op->setLabel($label);
            $op->setType($direction);
            $op->setSerialId($serial);

            $ops[] = $op;
            $serial++;
        }
        return $ops;
    }

    private function registerUtilityFunctions() {

        $storage = $this->storage;
        
        $this->registerFx('publicId', function (array $args, string $id) use ($storage) {
            //var_dump($id);
            $wallet  = $storage->findWalletBy(['walletPublicId' => $id]);

            if (null === $wallet) {
                throw new WalletException(sprintf('the wallet with public Id: %s doesn\'t exist', $id));
            }

            return $wallet->getWalletId();
        });

        $this->registerFx('glCode', function (array $args, string $id) use ($storage) {
            $wallet  = $storage->findWalletBy(['glCode' => $id]);

            if (null === $wallet) {
                throw new WalletException(sprintf('the wallet with GL code: %s doesn\'t exist', $id));
            }

            return $wallet->getWalletId();
        });
    }
}
