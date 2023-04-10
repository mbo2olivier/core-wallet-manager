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
use Mukadi\Wallet\Core\HolderInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\EntryInterface;
use Mukadi\Wallet\Core\PlatformInterface;
use Mukadi\Wallet\Core\Instruction;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
use Mukadi\Wallet\Core\SchemaInterface;

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
     * @param bool $autocommit
     * @return WalletInterface
     * @throws StorageLayerException
     **/
    public abstract function saveWallet(WalletInterface $wallet, bool $autocommit = true);

    /**
     * save entry in the storage layer
     * 
     * @param EntryInterface $op 
     * @param bool $autocommit
     * @return EntryInterface
     * @throws StorageLayerException
     **/
    abstract public function saveEntry(EntryInterface $op, bool $autocommit = true): EntryInterface;

    /**
     * save holder in the storage layer
     * 
     * @param HolderInterface $holder 
     * @param bool $autocommit
     * @return HolderInterface
     * @throws StorageLayerException
     **/
    public abstract function saveHolder(HolderInterface $holder, bool $autocommit = true): HolderInterface;

    /**
     * save authorization in the storage layer
     * 
     * @param AuthorizationInterface $auth 
     * @param bool $autocommit
     * @return AuthorizationInterface
     * @throws StorageLayerException
     **/
    public abstract function saveAuthorization(AuthorizationInterface $auth, bool $autocommit = true): AuthorizationInterface;

    /**
     * getting wallet by criteria
     *
     * @param array $criteria
     * @return null|WalletInterface
     * @throws StorageLayerException
     **/
    public abstract function findWalletBy(array $criteria): ?WalletInterface;

    /**
     * getting entry by criteria
     *
     * @param array $criteria
     * @return null|EntryInterface
     * @throws StorageLayerException
     **/
    public abstract function findEntryBy(array $criteria): ?EntryInterface;

    /**
     * getting holder by criteria
     *
     * @param array $criteria
     * @return null|HolderInterface
     * @throws StorageLayerException
     **/
    public abstract function findHolderBy(array $criteria): ?HolderInterface;

    /**
     * getting authorization by criteria
     *
     * @param array $criteria
     * @return null|AuthorizationInterface
     * @throws StorageLayerException
     **/
    public abstract function findAuthorizationBy(array $criteria): ?AuthorizationInterface;

    /**
     * find previous authorization by request id
     *
     * @param string $requestId
     * @return null|AuthorizationInterface
     * @throws StorageLayerException
     **/
    public abstract function findPreviousAuthorization(string $requestId, string $operationCode): ?AuthorizationInterface;

    /**
     * find all wallets by their ids
     *
     * @param string[] $walletIds
     * @return array<string,WalletInterface>
     * @throws StorageLayerException
     **/
    public abstract function findAllWalletsById(array $walletIds): iterable;

    /**
     * getting schema by criteria
     *
     * @param array $criteria
     * @return null|SchemaInterface
     * @throws StorageLayerException
     **/
    public abstract function findSchemaBy(array $criteria): ?SchemaInterface;

    /**
     * getting platform by id
     *
     * @param string $id
     * @return PlatformInterface
     * @throws StorageLayerException
     **/
    public abstract function getPlatform($id): ?PlatformInterface;

    /**
     * getting entries by criteria
     *
     * @param array $criteria
     * @return EntryInterface[]
     * @throws StorageLayerException
     **/
    public abstract function listEntryBy(array $criteria): iterable;

    /**
     * @param string $code;
     * @return Instruction[]
     */
    public abstract function getInstructions($code): iterable;

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

        if (0 === strpos($method, 'findEntryBy')) {
            return $this->resolveFindByCall('findEntryBy', substr($method, 11), $arguments);
        }

        if (0 === strpos($method, 'findAuthorizationBy')) {
            return $this->resolveFindByCall('findAuthorizationBy', substr($method, 19), $arguments);
        }

        if (0 === strpos($method, 'findHolderBy')) {
            return $this->resolveFindByCall('findHolderBy', substr($method, 12), $arguments);
        }

        if (0 === strpos($method, 'findSchemaBy')) {
            return $this->resolveFindByCall('findSchemaBy', substr($method, 12), $arguments);
        }

        throw new \BadMethodCallException(
            "Undefined method '$method'"
        );
    }
}
