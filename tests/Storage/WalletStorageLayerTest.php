<?php
namespace Mukadi\Wallet\Core\Test\Storage;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\HolderInterface;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;

class WalletStorageLayerTest extends Testcase {

    private $storage;

    protected function setUp(): void {
        $this->storage = $this
            ->getMockBuilder(WalletStorageLayer::class)
            ->setMethods(['beginTransaction','commit','rollback','saveWallet','saveOperation','saveHolder','saveAuthorization','findWalletBy','findOperationBy','findHolderBy','findAuthorizationBy','getPlatform','listOperationBy'])
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

    public function testFindOperationBy() {
        $w = $this->getMockBuilder(OperaionInterface::class)->getMock();
        
        $this->storage
            ->method('findOperationBy')
            ->willReturn($w)
        ;

        $this->assertInstanceOf(OperaionInterface::class, $this->storage->findOperationByOperationId("WA44"));
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

    public function testFindTransactionByException() {
        $w = $this->getMockBuilder(AuthorizationInterface::class)->getMock();
        
        $this->storage
            ->method('findAuthorizationBy')
            ->willReturn($w)
            ->willReturn($w)
        ;

        $this->expectException(\BadMethodCallException::class);

        $this->storage->findAuthByAuthorizationId("WA44");
    }
}