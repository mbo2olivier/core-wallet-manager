<?php
namespace Mukadi\Wallet\Core\Test\Expression;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Expression\Proxy;
use Mukadi\Wallet\Core\Test\Payment;

class ProxyTest extends TestCase {

    public function testSetMethod() {
        $r = new Payment();
        $proxy = new Proxy($r);
        $proxy->operationCode = "SCHM02";

        $this->assertEquals($r->getOperationCode(),'SCHM02');
    }

    public function testGetMethod() {
        $r = new Payment("SCHM02");
        $proxy = new Proxy($r);

        $this->assertEquals($proxy->operationCode,'SCHM02');
    }

    public function testSetMethodException() {
        $r = new Payment();
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo = "foo";
    }

    public function testGetMethodException() {
        $r = new Payment();
        $r->setOperationCode('foo');
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo;
    }
}