<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Storage;

use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\HolderInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\PlatformInterface;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
/**
 * class WalletStorageLayer.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class WalletStorageLayer
{
    use FindByCallResolver;
    /**
     * begin a transaction for a batch database operations
     */
    public abstract function beginTransaction();
    /**
     * validate database batch operations
     */
    public abstract function commit();
    /**
     * cancel database batch operations
     */
    public abstract function rollback();
    
    /**
     * save wallet in the storage layer
     * 
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws StorageLayerException
     **/
    public abstract function saveWallet(WalletInterface $wallet);

    /**
     * save operation in the storage layer
     * 
     * @param OperationInterface $op 
     * @return OperationInterface
     * @throws StorageLayerException
     **/
    abstract public function saveOperation(OperationInterface $op);

    /**
     * save holder in the storage layer
     * 
     * @param HolderInterface $holder 
     * @return HolderInterface
     * @throws StorageLayerException
     **/
    public abstract function saveHolder(HolderInterface $holder);

    /**
     * save authorization in the storage layer
     * 
     * @param AuthorizationInterface $auth 
     * @return AuthorizationInterface
     * @throws StorageLayerException
     **/
    public abstract function saveAuthorization(AuthorizationInterface $auth);

    /**
     * getting wallet by criteria
     *
     * @param array $criteria
     * @return WalletInterface
     * @throws StorageLayerException
     **/
    public abstract function findWalletBy(array $criteria);

    /**
     * getting operation by criteria
     *
     * @param array $criteria
     * @return OperationInterface
     * @throws StorageLayerException
     **/
    public abstract function findOperationBy(array $criteria);

    /**
     * getting holder by criteria
     *
     * @param array $criteria
     * @return HolderInterface
     * @throws StorageLayerException
     **/
    public abstract function findHolderBy(array $criteria);

    /**
     * getting authorization by criteria
     *
     * @param array $criteria
     * @return AuthorizationInterface
     * @throws StorageLayerException
     **/
    public abstract function findAuthorizationBy(array $criteria);

    /**
     * getting platform by id
     *
     * @param string $id
     * @return PlatformInterface
     * @throws StorageLayerException
     **/
    public abstract function getPlatform($id);

    /**
     * getting operations by criteria
     *
     * @param array $criteria
     * @return OperationInterface[]
     * @throws StorageLayerException
     **/
    public abstract function listOperationBy(array $criteria);

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
        if (0 === strpos($method, 'findWalletBy')) {
            return $this->resolveFindByCall('findWalletBy', substr($method, 12), $arguments);
        }

        if (0 === strpos($method, 'findOperationBy')) {
            return $this->resolveFindByCall('findOperationBy', substr($method, 15), $arguments);
        }

        if (0 === strpos($method, 'findAuthorizationBy')) {
            return $this->resolveFindByCall('findAuthorizationBy', substr($method, 19), $arguments);
        }

        if (0 === strpos($method, 'findHolderBy')) {
            return $this->resolveFindByCall('findHolderBy', substr($method, 12), $arguments);
        }

        throw new \BadMethodCallException(
            "Undefined method '$method'"
        );
    }
}
