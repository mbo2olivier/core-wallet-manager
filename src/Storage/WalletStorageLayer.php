<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Storage;

use Mukadi\Wallet\Core\Lien;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\HolderInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\Codes;
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
     * @param callable $transaction;
     * @return mixed
     */
    public function transactional(callable $transaction): mixed {
        $this->beginTransaction();

        try {
            $result = $transaction($this);
            $this->commit();

            return $result;
        }
        catch(\Throwable $e) {
            $this->rollback();

            throw $e;
        }
    }
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
    public abstract function saveWallet(WalletInterface $wallet, bool $autocommit = true): WalletInterface;

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
     * save lien in the storage layer
     * 
     * @param Lien $lien 
     * @param bool $autocommit
     * @return Lien
     * @throws StorageLayerException
     **/
    public abstract function saveLien(Lien $lien, bool $autocommit = true): Lien;

    /**
     * @param Lien $lien
     * @param string $authorizationId
     * @param int $serial
     * @param bool $autocommit
     * @return Lien
     * @throws StorageLayerException
     */
    public function markReadyForConsumption(Lien $lien, string $authorizationId, int $serial, ?string $amount = null): Lien {
        if ($lien->getStatus() !== Codes::LIEN_STATUS_ACTIVE) {
            throw new StorageLayerException('cannot consume non active lien');
        }

        $amount ??= $lien->getAmount();
        $lien->setAmount($lien->getAmount() - $amount);
        $lien->setAuthorizationId($authorizationId);
        $lien->setSerialId($serial);

        if ($lien->getAmount() == 0) {
            $lien->setStatus(Codes::LIEN_STATUS_CONSUMED);
        }
        
        $this->validateLien($lien);
        $lien = $this->saveLien($lien, false);
        $this->onLienMarkedAsConsumed($lien);

        return $lien;
    }

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
     * @return null|PlatformInterface
     * @throws StorageLayerException
     **/
    public abstract function getPlatform($id): ?PlatformInterface;

    /**
     * getting schema by id
     *
     * @param string $id
     * @return null|SchemaInterface
     * @throws StorageLayerException
     **/
    public abstract function getSchema($id): ?SchemaInterface;

    /**
     * getting wallet by id
     *
     * @param string $id
     * @return null|WalletInterface
     * @throws StorageLayerException
     **/
    public abstract function getWallet($id): ?WalletInterface;

    /**
     * getting entries by criteria
     *
     * @param array $criteria
     * @return EntryInterface[]
     * @throws StorageLayerException
     **/
    public abstract function listEntryBy(array $criteria): iterable;

    /**
     * @return array<Lien>
     */
    protected abstract function getRelatedActiveLiens(string $walletId): array;

    /**
     * @return array<Lien>
     */
    public function getRelatedSortedActiveLiens(string $walletId): array {
        $liens = $this->getRelatedActiveLiens($walletId);

        usort($liens, function (Lien $a, Lien $b): int {
            $res = $a->getOperationId() <=> $b->getOperationId();
            if ($res !== 0 && (empty($a->getOperationId()) || empty($b->getOperationId()))) {
                return $res;
            }

            $res = $a->getOperationCode() <=> $b->getOperationCode();
            if ($res !== 0 && (empty($a->getOperationCode()) || empty($b->getOperationCode()))) {
                return $res;
            }

            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });

        return array_reverse($liens);
    }

    /**
     * @param string $code;
     * @return Instruction[]
     */
    public abstract function getInstructions($code): iterable;

    public function createNewLien(string $walletId, string $amount, string $reason, ?string $operationCode = null, ?string $operationId = null, bool $autosave = true): Lien {
        $lien = \call_user_func([$this->getLienConcreteClass(), 'createNewInstance'], $walletId, $amount, $reason, $operationCode, $operationId);
        $this->validateLien($lien);

        if ($autosave) {
            return $this->saveLien($lien, true);
        }
        
        return $lien;
    }

    protected function onLienMarkedAsConsumed(Lien $lien): void {}

    protected function getLienConcreteClass(): string {
        return Lien::class;
    }

    protected function validateLien(Lien $lien) {
        if ($lien->getAmount() < 0) {
            throw new StorageLayerException('invalid lien amount: lien amount cannot be negative');
        }
    }

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
