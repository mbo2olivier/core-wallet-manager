<?php
namespace Mukadi\Wallet\Core\Test\Storage;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\TransactionInterface;
use Mukadi\Wallet\Core\Exception\StorageLayerException;
use Mukadi\Wallet\Core\Storage\TransactionStorageLayer;

class TransactionStorageLayerTest extends TestCase {

    use \Mukadi\Wallet\Core\Storage\FindByCallResolver;

    public function testResolveFindByCall() {

        $this->expectException(StorageLayerException::class);
        $this->assertArrayHasKey('foo', $this->resolveFindByCall("findBy", "foo", null));
    }

    public function testResolveFindByCallException() {

        $this->assertArrayHasKey('foo', $this->resolveFindByCall("findBy", "foo","bar"));
    }

    public function testFindTransactionBy() {
        $tx = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $storage = $this
            ->getMockBuilder(TransactionStorageLayer::class)
            ->setMethods(['findTransactionBy','saveTransaction','saveHistory'])
            ->getMock()
        ;
        $storage
            ->method('findTransactionBy')
            ->willReturn($tx)
        ;

        $this->assertInstanceOf(TransactionInterface::class, $storage->findTransactionByTransactionId("WA44"));
    }

    public function testFindTransactionByException() {
        $tx = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $storage = $this
            ->getMockBuilder(TransactionStorageLayer::class)
            ->setMethods(['findTransactionBy','saveTransaction','saveHistory'])
            ->getMock()
        ;
        $storage
            ->method('findTransactionBy')
            ->willReturn($tx)
        ;

        $this->expectException(\BadMethodCallException::class);

        $storage->findTxByWalletId("WA44");
    }

    private function findBy(array $c) {
        return ['foo' =>  "bar"];
    }
}