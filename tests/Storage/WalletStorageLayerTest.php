<?php
namespace Mukadi\Wallet\Core\Test\Storage;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\HolderInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\EntryInterface;
use Mukadi\Wallet\Core\PlatformInterface;
use Mukadi\Wallet\Core\SchemaInterface;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;

class WalletStorageLayerTest extends Testcase {

    private $storage;

    protected function setUp(): void {
        $this->storage = $this
            ->getMockBuilder(WalletStorageLayer::class)
            ->onlyMethods(['beginTransaction','commit','rollback','saveWallet','saveEntry','saveHolder','saveAuthorization','findWalletBy','findEntryBy','findHolderBy','findAuthorizationBy','findSchemaBy','getPlatform','listEntryBy', 'getInstructions', 'findAllWalletsById', 'findPreviousAuthorization', 'getSchema', 'getWallet', 'saveLien', 'getRelatedActiveLiens'])
            ->getMock()
        ;
    }

    public function testFindWalletBy() {
        $w = $this->getMockBuilder(WalletInterface::class)->getMock();
        
        $this->storage
            ->method('findWalletBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(WalletInterface::class, $this->storage->findWalletByWalletId("WA44"));
    }

    public function testfindEntryBy() {
        $w = $this->getMockBuilder(EntryInterface::class)->getMock();
        
        $this->storage
            ->method('findEntryBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(EntryInterface::class, $this->storage->findEntryById("WA44"));
    }

    public function testFindHolderBy() {
        $w = $this->getMockBuilder(HolderInterface::class)->getMock();
        
        $this->storage
            ->method('findHolderBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(HolderInterface::class, $this->storage->findHolderByHolderId("WA44"));
    }

    public function testFindAuthorizationBy() {
        $w = $this->getMockBuilder(AuthorizationInterface::class)->getMock();
        
        $this->storage
            ->method('findAuthorizationBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(AuthorizationInterface::class, $this->storage->findAuthorizationByAuthorizationId("WA44"));
    }

    public function testFindSchemaBy() {
        $w = $this->getMockBuilder(SchemaInterface::class)->getMock();
        
        $this->storage
            ->method('findSchemaBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(SchemaInterface::class, $this->storage->findSchemaById("SCM04"));
    }

    public function testFindPreviousAuthorization() {
        $w = $this->getMockBuilder(AuthorizationInterface::class)->getMock();
        
        $this->storage
            ->method('findPreviousAuthorization')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(AuthorizationInterface::class, $this->storage->findPreviousAuthorization("REQ1", "PYMT"));
    }

    public function testFindAllWalletsById() {
        $w = $this->getMockBuilder(WalletInterface::class)->getMock();
        
        $this->storage
            ->method('findAllWalletsById')
            ->willReturn([$w])
        ;

        $this->assertIsArray($this->storage->findAllWalletsById(["WA01"]));
    }

    public function testgetPlatform() {
        $w = $this->getMockBuilder(PlatformInterface::class)->getMock();
        
        $this->storage
            ->method('getPlatform')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(PlatformInterface::class, $this->storage->getPlatform("PL01"));
    }

    public function testFindTransactionByException() {
        $w = $this->getMockBuilder(AuthorizationInterface::class)->getMock();
        
        $this->storage
            ->method('findAuthorizationBy')
            ->willReturn($w)
        ;

        $this->expectException(\BadMethodCallException::class);

        $this->storage->findAuthByAuthorizationId("WA44");
    }
}