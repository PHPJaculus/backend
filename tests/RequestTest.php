<?php

use Jaculus\Request;
use Jaculus\ArrayWrapper;
use Jaculus\SessionArrayWrapper;
use Jaculus\DI;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase  {

    protected function setUp() {
        DI::setupJaculus(',', []);
    }

    protected function tearDown() {
        DI::destroy();
        $_GET = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = '';
        $_SERVER['REQUEST_URI'] = '';
    }

    function testMethod1() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/zebra';
        $r = new Request();
        $this->assertEquals('GET', $r->method);
    }

    function testMethod2() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/zebra';
        $r = new Request();
        $this->assertEquals('POST', $r->method);
    }

    function testUri() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';
        $r = new Request();
        $this->assertEquals('/foo', $r->uri);
    }

    function testVars() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';
        $r = new Request();
        $this->assertInstanceOf(ArrayWrapper::class, $r->get);
        $this->assertInstanceOf(ArrayWrapper::class, $r->post);
        $this->assertInstanceOf(ArrayWrapper::class, $r->files);
        $this->assertInstanceOf(ArrayWrapper::class, $r->request);
        $this->assertInstanceOf(ArrayWrapper::class, $r->cookies);
        $this->assertInstanceOf(SessionArrayWrapper::class, $r->session);
    }

    function testInputGetGet() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';
        $_GET = ['foo' => 'zebra'];
        $r = new Request();
        $foo = $r->input->foo;
        $this->assertEquals('zebra', $foo);
    }

    function testInputGetPost() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/foo';
        $_GET = ['foo' => 'zebra'];
        $r = new Request();
        $foo = $r->input->foo;
        $this->assertEquals(null, $foo);
    }

    function testInputPostPost() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/foo';
        $_POST = ['foo' => 'zebra'];
        $r = new Request();
        $foo = $r->input->foo;
        $this->assertEquals('zebra', $foo);
    }

    function testInputPostGet() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';
        $_POST = ['foo' => 'zebra'];
        $r = new Request();
        $foo = $r->input->foo;
        $this->assertEquals(null, $foo);
    }
}