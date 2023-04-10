<?php
namespace Mukadi\Wallet\Core\Test\Manager;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\Instruction;
use Mukadi\Wallet\Core\SchemaInterface;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;
use Mukadi\Wallet\Core\Test\Entry;
use Mukadi\Wallet\Core\Test\Payment;
use Mukadi\Wallet\Core\Test\SchemaManager;
use Mukadi\Wallet\Core\Test\Wallet;

class AbstractSchemaManagerTest extends TestCase {

    /** @var AuthorizationInterface */
    private $auth;
    /** @var SchemaManager */
    private $manager;

    public function setUp():void {
        // authorization
        /*$this->auth = $this
            ->getMockBuilder(AuthorizationInterface::class)
            ->onlyMethods(['getDescription','setDescription','getDate', 'setDate', 'getOperationCode', 'getOperationCode', 'setOperationCode', 'getOperationId', 'setOperationId',
                            'getAuthorizationId', 'setAuthorizationId', 'getStatus', 'setStatus','getInstrumentId', 'setInstrumentId', 'getHolderId', 'setHolderId',
                            'getPlatformId', 'setPlatformId', 'getEncodedBy', 'setEncodedBy', 'getValidatededBy', 'setValidatedBy', 'getEncodedAt', 'setEncodedAt',
                            'getValidatedAt', 'setValidatedAt', 'getSchemaId', 'setSchemaId', 'getTransactionAmount', 'setTransactionAmount', 'getCurrency', 'setCurrency',
                            'getCommissionAmount', 'setCommissionAmount', 'getCommissionCurrency', 'setCommissionCurrency',
            ])
            ->getMock()
        ;
        $this->auth->method('getData1')->willReturn("MBOMBO");
        $this->auth->method('getTransactionAmount')->willReturn(10);
        $this->auth->method('getCurrency')->willReturn('USD');
        $this->auth->method('getCommissionAmount')->willReturn(500);
        $this->auth->method('getCommissionCurrency')->willReturn('CDF');
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
        */

        /** @var WalletStorageLayer $storage */
        $storage = $this
            ->getMockForAbstractClass(WalletStorageLayer::class)
            //->onlyMethods(['getInstructions', 'findSchemaBy'])
            //->getMock()
        ;

        /** @var SchemaInterface $schmOne */
        $schmOne = $this
            ->getMockBuilder(SchemaInterface::class)
            //->onlyMethods(['getInstructions', 'findSchemaBy'])
            ->getMock()
        ;

        $storage
            ->method('getInstructions')
            ->willReturn([
                new Instruction(1,'t.amount / 2','t.currency','D','FIRST TRANSACTION','SCHM01','WA001', Instruction::AMOUNT_COMPUTED|Instruction::CURRENCY_COMPUTED),
                new Instruction(2,'t.amount','t.currency','C','upper(t.description)','SCHM01','publicId("20220022")', Instruction::AMOUNT_COMPUTED|Instruction::CURRENCY_COMPUTED|Instruction::LABEL_COMPUTED|Instruction::WALLET_COMPUTED),
            ])
        ;

        $storage
            ->method('findSchemaBy')
            ->willReturn($schmOne)
        ;

        $w = new Wallet();
        $w->setWalletId("WA002");
        $w->setGlCode("17100");
        $w->setWalletPublicId("20220022");

        $storage
            ->method('findWalletBy')
            ->willReturn($w)
        ;

        $this->manager = new SchemaManager($storage, Entry::class);
        $this->manager->registerFx('upper',new UpperFx);
    }

    /** @test */
    public function TestGetSchemaFor() {
        $p = new Payment('TX1');
        $p->amount = 10;
        $p->currency = "USD";
        $p->walletId = "WA002";
        $ops = $this->manager->getSchemaFor($p);

        $this->assertCount(2, $ops);
        $op = $ops[0];

        $this->assertEquals($op->getTransactionCurrency(),$p->getCurrency());
        $this->assertEquals($op->getTransactionAmount(),5);
        $this->assertEquals($op->getWalletId(),'WA001');
        $this->assertEquals($op->getType(),'D');

        $op = $ops[1];
        $this->assertEquals($op->getTransactionAmount(),$p->getTransactionAmount());
        $this->assertEquals($op->getLabel(),strtoupper($p->getDescription()));
        $this->assertEquals($op->getWalletId(), "WA002");
    }
}


class UpperFx {

    public function __invoke($a, $label) {
        return \strtoupper($label);
    }
}