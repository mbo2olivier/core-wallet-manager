<?php
namespace Mukadi\Wallet\Core\Test\Manager;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Test\TransactionHistory;
use Mukadi\Wallet\Core\Test\Transaction;
use Mukadi\Wallet\Core\Storage\TransactionStorageLayer;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\Test\TransactionManager;
use Mukadi\Wallet\Core\Exception\TransactionException;

class AbstractTransactionManagerTest extends TestCase {
    /** @var TransactionStorageLayer */
    private $storage;
    /** @var TransactionManager */
    private $manager;

    public function setUp():void {
        $this->storage = $this
                            ->getMockBuilder(TransactionStorageLayer::class)
                            ->setMethods(['saveTransaction','saveHistory','findTransactionBy'])
                            ->getMock()
        ;

        $this->storage->method('saveTransaction')->will(
            $this->returnCallback(function ($tx) {
                return $tx;
            })
        );

        $this->manager = new TransactionManager($this->storage, TransactionHistory::class);
    }

    /** @test */
    public function testOpen() {
        $tx = new Transaction();

        $tx = $this->manager->open($tx);

        $this->assertEquals($tx->getStatus(), Codes::TX_STATUS_OPENED);
        $this->assertEquals($tx->getTransactionId(), "TX001");
    }

    public function testCloseTxNotFound() {
        $this->expectException(TransactionException::class);

        $this->manager->close('TX001');
    }

    public function testCloseTxNotOpened() {
        $tx = new Transaction();
        $tx->setStatus(Codes::TX_STATUS_TERMINATED);
        $this
            ->storage
            ->method('findTransactionBy')->willReturn($tx);
        ;
        $this->expectException(TransactionException::class);

        $this->manager->close('TX001');
    }

    public function testCloseWrongStatus() {
        $tx = new Transaction();
        $tx->setStatus(Codes::TX_STATUS_OPENED);
        $this
            ->storage
            ->method('findTransactionBy')->willReturn($tx);
        ;
        $this->expectException(TransactionException::class);

        $this->manager->close('TX001','F');
    }

    public function testClose() {
        $tx = new Transaction();
        $tx->setStatus(Codes::TX_STATUS_OPENED);
        $this
            ->storage
            ->method('findTransactionBy')->willReturn($tx);
        ;
        $tx = $this->manager->close('TX001');

        $this->assertEquals($tx->getStatus(), Codes::TX_STATUS_CANCELED);
        $this->assertNotNull($tx->getEndedAt());
    }

}