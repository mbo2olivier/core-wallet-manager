<?php
namespace Mukadi\Wallet\Core\Test\Manager;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\InstructionInterface;
use Mukadi\Wallet\Core\OperationInterface;
use Mukadi\Wallet\Core\Storage\SchemaStorageLayer;
use Mukadi\Wallet\Core\Test\Operation;
use Mukadi\Wallet\Core\Test\SchemaManager;

class AbstractSchemaManagerTest extends TestCase {

    /** @var AuthorizationInterface */
    private $auth;
    /** @var SchemaManager */
    private $manager;

    public function setUp():void {
        // authorization
        $this->auth = $this
            ->getMockBuilder(AuthorizationInterface::class)
            ->setMethods(['getAmount','getCurrency','getType','getBalance','setBalance','setAmount','setCurrency','getCode','setCode','setType','getAuthorizationId','setAuthorizationId','getStatus','setStatus','getWalletId','setWalletId','getBufferWalletId','setBufferWalletId','getChannelId','setChannelId','getAuthorizationRef','setAuthorizationRef','getRequester','setRequester','getPlatformId','setPlatformId','getData1','setData1','getData2','setData2','getData3','setData3','getData4','setData4','getData5','setData5','getData6','setData6'])
            ->getMock()
        ;
        $this->auth->method('getData1')->willReturn("MBOMBO");
        $this->auth->method('getAmount')->willReturn(10);
        $this->auth->method('getCurrency')->willReturn('USD');
        $this->auth->method('getWalletId')->willReturn('WA001');
        $this->auth->method('getType')->willReturn(Codes::AUTH_STATUS_PENDING);
        // instruction
        $instruction = $this
            ->getMockBuilder(InstructionInterface::class)
            ->setMethods(['getSchemaId','setSchemaId','getWallet','setWallet','getAmount','setAmount','getCurrency','setCurrency','getDirection','setDirection','getLabel','setLabel','getOrder','setOrder'])
            ->getMock()
        ;
        $instruction->method('getAmount')->willReturn('t.amount / 2');
        $instruction->method('getCurrency')->willReturn('t.currency');
        $instruction->method('getWallet')->willReturn('t.walletId');
        $instruction->method('getLabel')->willReturn('upper("hello ") ~ t.data1');
        $instruction->method('getDirection')->willReturn('"D"');

        $storage = $this
            ->getMockBuilder(SchemaStorageLayer::class)
            ->setMethods(['getInstructions'])
            ->getMock()
        ;

        $storage
            ->method('getInstructions')
            ->willReturn([$instruction])
        ;

        $this->manager = new SchemaManager($storage, Operation::class);
        $this->manager->registerFx('upper',new UpperFx);
    }

    /** @test */
    public function TestGetSchemaFor() {
        $ops = $this->manager->getSchemaFor($this->auth);

        $this->assertCount(1, $ops);
        $op = $ops[0];

        $this->assertEquals($op->getAmount(),5);
        $this->assertEquals($op->getCurrency(),'USD');
        $this->assertEquals($op->getWalletId(),'WA001');
        $this->assertEquals($op->getType(),'D');
    }

    /** @test */
    public function TestGetSchemaForWithFx() {
        $ops = $this->manager->getSchemaFor($this->auth);
        $op = $ops[0];

        $this->assertEquals($op->getLabel(),'HELLO MBOMBO');
    }
}


class UpperFx {

    public function __invoke($a, $label) {
        return \strtoupper($label);
    }
}