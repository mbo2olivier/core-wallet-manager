<?php
namespace Mukadi\Wallet\Core\Test\Manager;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Exception\AuthorizationException;
use Mukadi\Wallet\Core\Exception\BalanceException;
use Mukadi\Wallet\Core\Exception\WalletException;
use Mukadi\Wallet\Core\Exception\OperationException;
use Mukadi\Wallet\Core\Storage\WalletStorageLayer;
use Mukadi\Wallet\Core\Manager\AbstractSchemaManager;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\Request;
use Mukadi\Wallet\Core\Reversal;
use Mukadi\Wallet\Core\Test\Operation;
use Mukadi\Wallet\Core\Test\Authorization;
use Mukadi\Wallet\Core\Test\Instruction;
use Mukadi\Wallet\Core\Test\WalletManager;
use Mukadi\Wallet\Core\Test\Wallet;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\Test\SchemaManager;
use Mukadi\Wallet\Core\Storage\SchemaStorageLayer;

class AbstractWalletManagerTest extends TestCase {

    /** @var WalletStorageLayer */
    private $storage;
    /** @var AbstractSchemaManager */
    private $schema;

    public function setUp():void {
        $this->schema = $this
                            ->getMockBuilder(AbstractSchemaManager::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getSchemaFor'])
                            ->getMock()
        ;
        $this->storage = $this
                            ->getMockBuilder(WalletStorageLayer::class)
                            ->setMethods(['beginTransaction','commit','rollback','saveWallet','saveOperation','saveHolder','saveAuthorization','findWalletBy','findOperationBy','findHolderBy','findAuthorizationBy','getPlatform','listOperationBy'])
                            ->getMock()
        ;

        $this->storage->method('saveWallet')->will(
            $this->returnCallback(function ($w) {
                return $w;
            })
        );
    }

    /** @test */
    public function testOpen() {
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $w = new Wallet();

        $w = $manager->openWallet($w);

        $this->assertFalse($w->isClosed());
        $this->assertNotNull($w->getCreatedAt());
        $this->assertEquals($w->getWalletId(),'WA0001');
    }

    /** @test */
    public function testClosedWalletNotFound() {
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->closeWallet('FOOBAR');
    }

    /** @test */
    public function testClosedWalletClosed() {
        $w = new Wallet();
        $w->setClosed(true);
        $this->storage->method('findWalletBy')->willReturn($w);
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->closeWallet('FOOBAR');
    }

    /** @test */
    public function testClosed() {
        $w = new Wallet();
        $w->setClosed(false);
        $this->storage->method('findWalletBy')->willReturn($w);
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $w = $manager->closeWallet('FOOBAR');

        $this->assertNotNull($w->getClosedAt());
        $this->assertTrue($w->isClosed());
    }

    /** @test */
    public function testExecuteWhenOperationAlreadyExist() {
        $op = new Operation();
        $op->setStatus(Codes::OPERATION_TYPE_CASH_IN);

        $this->storage->method('findOperationBy')->willReturn($op);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(OperationException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op]);
    }

    /** @test */
    public function testExecuteWhenWaletNotFound() {
        $op = new Operation();
        $op->setStatus(Codes::OPERATION_TYPE_CASH_IN);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(OperationException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op]);
    }

    /** @test */
    public function testExecuteWhenCurrencyMismatch() {
        $op = new Operation();
        $op->setStatus(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');

        $w = new Wallet();
        $w->setCurrency('USD');

        $this->storage->method('findWalletBy')->willReturn($w);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(OperationException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op]);
    }

    /** @test */
    public function testExecuteWhenNotAuthorized() {
        $op = new Operation();
        $op->setStatus(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');

        $w = new Wallet();
        $w->setCurrency('CDF');

        $this->storage->method('findWalletBy')->willReturn($w);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op]);
    }

    /** @test */
    public function testExecuteWhenAuthorizationNotPending() {
        $op = new Operation();
        $op->setStatus(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');

        $w = new Wallet();
        $w->setCurrency('CDF');

        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);

        $this->storage->method('findWalletBy')->willReturn($w);
        $this->storage->method('findAuthorizationBy')->willReturn($auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op]);
    }

    /* @test */
    public function testExecute() {
        $in = new Operation();
        $in->setType(Codes::OPERATION_TYPE_CASH_IN);
        $in->setCurrency('CDF');
        $in->setAmount(1500);

        $out = new Operation();
        $out->setType(Codes::OPERATION_TYPE_CASH_OUT);
        $out->setCurrency('CDF');
        $out->setAmount(500);

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);

        $this->storage->method('findWalletBy')->willReturn($w);
        $this->storage->method('findAuthorizationBy')->willReturn($auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $in = $execute->invokeArgs($manager, [$in]);

        $this->assertEquals($in->getStatus(), Codes::OPERATION_STATUS_SUCCESS);
        $this->assertNotNull($in->getValidatedAt());
        $this->assertNotNull($w->getBalanceUpdatedAt());
        $this->assertEquals($in->getBalance(), $w->getBalance());
        $this->assertEquals($in->getBalance(), 2500);

        $out = $execute->invokeArgs($manager, [$out]);
        $this->assertEquals($out->getBalance(), $w->getBalance());
        $this->assertEquals($out->getBalance(), 2000);
    }

    protected static function getProtectedMethod($class, $name) {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /** @test */
    public function testAuthorizeWhenWalletNotFound() {
        $r = new Request();

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->authorize($r);
    }

    /** @test */
    public function testAuthorizeWhenCurrencyMismatch() {
        $r = new Request();
        $r->setCurrency('USD');

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $this->storage->method('findWalletBy')->willReturn($w);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->authorize($r);
    }

    /** @test */
    public function testAuthorizeWhenBufferWalletNotFound() {
        $r = new Request();
        $r->setCurrency('CDF');
        $r->setAmount(500);
        $r->setAuthorizationRef('REF001');
        $r->setWalletId('W0001');
        $r->setBufferWalletId('W0002');

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $this->storage->method('findWalletBy')->will($this->returnCallback(function($a) use ($w){
            if($a['walletId'] == 'W0001')
                return $w;
            else 
                return null;
        }));

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->authorize($r);
    }

    /** @test */
    public function testAuthorizeWhenBufferCurrencyMismatch() {
        $r = new Request();
        $r->setCurrency('CDF');
        $r->setAmount(500);
        $r->setAuthorizationRef('REF001');
        $r->setWalletId('W0001');
        $r->setBufferWalletId('W0002');

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $b = new Wallet();
        $b->setCurrency('USD');
        $b->setBalance(1000);

        $this->storage->method('findWalletBy')->will($this->returnCallback(function($a) use ($w, $b){
            if($a['walletId'] == 'W0001')
                return $w;
            else if($a['walletId'] == 'W0002')
                return $b;
            else 
                return null;
        }));

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(WalletException::class);

        $manager->authorize($r);
    }

    /** @test */
    public function testAuthorizeWhenBalanceInsuffisent() {
        $r = new Request();
        $r->setCurrency('CDF');
        $r->setAmount(2000);

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $this->storage->method('findWalletBy')->willReturn($w);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(BalanceException::class);

        $manager->authorize($r);
    }

    /** @test */
    public function testAuthorize() {
        $r = new Request();
        $r->setCurrency('CDF');
        $r->setAmount(500);
        $r->setAuthorizationRef('REF001');
        $r->setWalletId('W0001');
        $r->setBufferWalletId('W0002');

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $b = new Wallet();
        $b->setCurrency('CDF');
        $b->setBalance(1000);

        $_auth = new Authorization();
        $_auth->setStatus(Codes::AUTH_STATUS_PENDING);        

        $this->storage->method('findWalletBy')->will($this->returnCallback(function ($a) use ($w, $b) {
            return $a['walletId'] == 'W0001' ? $w : $b;
        }));
        $this->storage->method('saveAuthorization')->will($this->returnCallback(function ($a) {
            return $a;
        }));
        $this->storage->method('findAuthorizationBy')->willReturn($_auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $auth = $manager->authorize($r);

        $this->assertEquals(500, $auth->getAmount());
        $this->assertEquals('CDF', $auth->getCurrency());
        $this->assertEquals('REF001', $auth->getAuthorizationRef());
        $this->assertEquals('A0001', $auth->getAuthorizationId());
        $this->assertEquals(Codes::AUTH_STATUS_PENDING, $auth->getStatus());
        $this->assertEquals(500, $w->getBalance());
        $this->assertEquals(1500, $b->getBalance());

    }

    public function testAuthorizationRedemptionWhenAuthNotFound() {
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationRedemption("A0001");
    }

    public function testAuthorizationRedemptionWhenAuthNotPending() {
        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);

        $this->storage->method('findAuthorizationBy')->willReturn($auth);
        
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationRedemption("A0001");
    }

    public function testAuthorizationRedemptionWhenAuthNotDebit() {
        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setType(Codes::AUTH_TYPE_REVERSE);

        $this->storage->method('findAuthorizationBy')->willReturn($auth);
        
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationRedemption("A0001");
    }

    public function testAuthorizationRedemption() {
        $auth = new Authorization;
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setType(Codes::AUTH_TYPE_DEBIT);
        $auth->setAmount(1000);
        $auth->setCurrency('CDF');
        $auth->setCode('CODE');
        $auth->setAuthorizationId('A0001');
        $auth->setAuthorizationRef('REF01');
        $auth->setWalletId('W001');
        $auth->setBufferWalletId('W002');

        $w = new Wallet;
        $w->setWalletId('W001');
        $w->setBalance(500);
        $w->setCurrency('CDF');

        $b = new Wallet;
        $b->setWalletId('W002');
        $b->setBalance(1000);
        $b->setCurrency('CDF');

        $p = new Wallet;
        $p->setWalletId('W003');
        $p->setBalance(0);
        $p->setCurrency('CDF');

        $d = new Wallet;
        $d->setWalletId('W004');
        $d->setBalance(50);
        $d->setCurrency('CDF');

        $this->storage->method('findWalletBy')->will($this->returnCallback(function ($a) use ($w, $b, $p, $d) {
            if($a['walletId'] == 'W001')
                return $w;
            elseif($a['walletId'] == 'W002')
                return $b;
            elseif($a['walletId'] == 'W003')
                return $p;   
            elseif($a['walletId'] == 'W004')
                return $d;   
            else
                return null;     
        }));
        $this->storage->method('saveAuthorization')->will($this->returnCallback(function ($a) {
            return $a;
        }));
        $this->storage->method('findAuthorizationBy')->willReturn($auth);

        $iStorage = $this
            ->getMockBuilder(SchemaStorageLayer::class)
            ->setMethods(['getInstructions'])
            ->getMock()
        ;

        $iStorage->method('getInstructions')->willReturn([
            new Instruction('t.amount','t.currency','"D"','"foo bar 1"',0,'S001','t.bufferWalletId'),
            new Instruction('t.amount * 0.95','t.currency','"C"','"foo bar 2"',1,'S001','"W004"'),
            new Instruction('t.amount * 0.05','t.currency','"C"','"foo bar 3"',2,'S001','"W003"'),
        ]);
        
        $manager = new WalletManager(new SchemaManager($iStorage, Operation::class), $this->storage, Authorization::class, Operation::class);

        $auth = $manager->authorizationRedemption("A0001");

        $this->assertEquals(Codes::AUTH_STATUS_FINALIZED, $auth->getStatus());
        $this->assertEquals(500, $w->getBalance());
        $this->assertEquals(0, $b->getBalance());
        $this->assertEquals(1000, $d->getBalance());
        $this->assertEquals(50, $p->getBalance());
    }

    public function testAuthorizationReversalWhenAuthNotFound() {
        $r = new Reversal;
        $r->setPreviousAuthId('A0002');
        $r->setMaker('TEST');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationReversal($r);
    }

    public function testAuthorizationReversalWhenAuthNotFinalized() {
        $auth = new Authorization;
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setType(Codes::AUTH_TYPE_DEBIT);
        $auth->setAmount(1000);
        $auth->setCurrency('CDF');
        $auth->setCode('CODE');
        $auth->setAuthorizationId('A0001');
        $auth->setAuthorizationRef('REF01');
        $auth->setWalletId('W001');
        $auth->setBufferWalletId('A0001');

        $r = new Reversal;
        $r->setPreviousAuthId('A0002');
        $r->setMaker('TEST');

        $this->storage->method('findAuthorizationBy')->willReturn($auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationReversal($r);
    }

    public function testAuthorizationReversalWhenAuthNotDebit() {
        $auth = new Authorization;
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);
        $auth->setType(Codes::AUTH_TYPE_REVERSE);
        $auth->setAmount(1000);
        $auth->setCurrency('CDF');
        $auth->setCode('CODE');
        $auth->setAuthorizationId('A0001');
        $auth->setAuthorizationRef('REF01');
        $auth->setWalletId('W001');
        $auth->setBufferWalletId('A0001');

        $r = new Reversal;
        $r->setPreviousAuthId('A0002');
        $r->setMaker('TEST');

        $this->storage->method('findAuthorizationBy')->willReturn($auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $this->expectException(AuthorizationException::class);

        $manager->authorizationReversal($r);
    }

    public function testAuthorizationReversal() {
        $auth = new Authorization;
        $auth->setStatus(Codes::AUTH_STATUS_FINALIZED);
        $auth->setType(Codes::AUTH_TYPE_DEBIT);
        $auth->setAmount(1000);
        $auth->setCurrency('CDF');
        $auth->setCode('CODE');
        $auth->setAuthorizationId('A0002');
        $auth->setAuthorizationRef('REF01');
        $auth->setWalletId('W001');
        $auth->setBufferWalletId('A0001');
        $auth->setBalance(0);

        $r = new Reversal;
        $r->setPreviousAuthId('A0002');
        $r->setMaker('TEST');

        $w = new Wallet;
        $w->setWalletId('WA');
        $w->setBalance(0);
        $w->setCurrency('CDF');

        $b = new Wallet;
        $b->setWalletId('BU');
        $b->setBalance(0);
        $b->setCurrency('CDF');

        $p = new Wallet;
        $p->setWalletId('PR');
        $p->setBalance(50);
        $p->setCurrency('CDF');

        $d = new Wallet;
        $d->setWalletId('DE');
        $d->setBalance(950);
        $d->setCurrency('CDF');

        $this->storage->method('findWalletBy')->will($this->returnCallback(function ($a) use ($w, $b, $p, $d) {
            if($a['walletId'] == 'WA')
                return $w;
            elseif($a['walletId'] == 'BU')
                return $b;
            elseif($a['walletId'] == 'PR')
                return $p;   
            elseif($a['walletId'] == 'DE')
                return $d;   
            else
                return null;     
        }));
        $this->storage->method('saveAuthorization')->will($this->returnCallback(function ($a) {
            return $a;
        }));

        $this->storage->method('findAuthorizationBy')->will($this->returnCallback(function ($a) use ($auth, $r){
            if($a['authorizationId'] == 'A0002')
                return $auth;
            else {
                $na = new Authorization;
                $na->setType(Codes::AUTH_TYPE_REVERSE);
                $na->setAmount($auth->getAmount());
                $na->setCurrency($auth->getCurrency());
                $na->setCode($auth->getCode());
                $na->setAuthorizationRef($auth->getAuthorizationRef());
                $na->setPlatformId($auth->getPlatformId());
                $na->setChannelId($auth->getChannelId());
                $na->setAuthorizationId('W0001');
                $na->setStatus(Codes::AUTH_STATUS_PENDING);
                $na->setBalance($auth->getBalance() + $auth->getAmount());
                $na->setWalletId($auth->getWalletId());
                $na->setRequester($r->getMaker());
                return $na;
            }
        }));
        $this->storage->method('listOperationBy')->willReturn([
            new Operation('OP01',1000, 'CDF', 'foo bar 1', Codes::OPERATION_TYPE_CASH_OUT, 'WA'),
            new Operation('OP02',1000, 'CDF', 'foo bar 2', Codes::OPERATION_TYPE_CASH_IN, 'BU'),
            new Operation('OP03',1000, 'CDF', 'foo bar 3', Codes::OPERATION_TYPE_CASH_OUT, 'BU'),
            new Operation('OP04',950, 'CDF', 'foo bar 4', Codes::OPERATION_TYPE_CASH_IN, 'DE'),
            new Operation('OP05',50, 'CDF', 'foo bar 5', Codes::OPERATION_TYPE_CASH_IN, 'PR'),
        ]);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class, Operation::class);

        $_auth = $manager->authorizationReversal($r);

        $this->assertEquals(Codes::AUTH_STATUS_REVERSED, $auth->getStatus());
        $this->assertEquals(Codes::AUTH_STATUS_FINALIZED, $_auth->getStatus());
        $this->assertEquals(Codes::AUTH_TYPE_REVERSE, $_auth->getType());
        $this->assertEquals(Codes::AUTH_TYPE_DEBIT, $auth->getType());
        $this->assertEquals($_auth->getBalance(), $w->getBalance());
        $this->assertEquals(1000, $w->getBalance());
        $this->assertEquals(0, $b->getBalance());
        $this->assertEquals(0, $p->getBalance());
        $this->assertEquals(0, $d->getBalance());
    }
}