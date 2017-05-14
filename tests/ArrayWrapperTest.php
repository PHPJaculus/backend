<?php

use Jaculus\ArrayWrapper;

use PHPUnit\Framework\TestCase;

class ArrayWrapperTest extends TestCase {

    function testItemNotExists() {
        $w = new ArrayWrapper(['foo' => 42, 'bar' => 'zebra']);
        $this->assertEquals($w->not_exists, null);
    }

    function testItemExists() {
        $w = new ArrayWrapper(['foo' => 42]);
        $this->assertEquals($w->foo, 42);
    }

    function testArrayLike() {
        $w = new ArrayWrapper(['foo' => 42]);
        $this->assertTrue(isset($w['foo']));
        $this->assertEquals($w['foo'], 42);
    }

    function testConst() {
        $this->expectException(Jaculus\Exception::class);
        $w = new ArrayWrapper([]);
        $w['bar'] = 42;
    }
}