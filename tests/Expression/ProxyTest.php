<?php
namespace Mukadi\Wallet\Core\Test\Expression;

use PHPUnit\Framework\TestCase;
use Mukadi\Wallet\Core\Expression\Proxy;
use Mukadi\Wallet\Core\Request;

class ProxyTest extends TestCase {

    public function testSetMethod() {
        $r = new Request();
        $proxy = new Proxy($r);
        $proxy->code = "foo";

        $this->assertEquals($r->getCode(),'foo');
    }

    public function testGetMethod() {
        $r = new Request();
        $r->setCode('foo');
        $proxy = new Proxy($r);

        $this->assertEquals($proxy->code,'foo');
    }

    public function testSetMethodException() {
        $r = new Request();
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo = "foo";
    }

    public function testGetMethodException() {
        $r = new Request();
        $r->setCode('foo');
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo;
    }
}