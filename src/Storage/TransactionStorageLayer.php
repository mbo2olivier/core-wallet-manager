<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Storage;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
use Mukadi\Wallet\Core\TransactionHistoryInterface;
use Mukadi\Wallet\Core\TransactionInterface;

/**
 * Class TransactionStorageLayer.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class TransactionStorageLayer
{
    use FindByCallResolver;
    /**
     * save transaction in the storage layer
     *
     * @param TransactionInterface $tx
     * @return TransactionInterface
     * @throws StorageLayerException
     **/
    public abstract function saveTransaction(TransactionInterface $tx);

    /**
     * save transaction in the storage layer
     *
     * @param TransactionHistoryInterface $h
     * @return TransactionHistoryInterface
     * @throws StorageLayerException
     **/
    public abstract function saveHistory(TransactionHistoryInterface $h);

    /**
     * getting wallet by criteria
     *
     * @param array $criteria
     * @return TransactionInterface
     * @throws StorageLayerException
     **/
    public abstract function findTransactionBy(array $criteria);

    /**
     * Adds support for magic method calls.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed The returned value from the resolved method.
     *
     * @throws StorageLayerException
     * @throws \BadMethodCallException If the method called is invalid
     */
    public function __call($method, $arguments) {
        if (0 === strpos($method, 'findTransactionBy')) {
            return $this->resolveFindByCall('findTransactionBy', substr($method, 17), $arguments);
        }

        throw new \BadMethodCallException(
            "Undefined method '$method'"
        );
    }
}
