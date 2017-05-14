<?php

use Jaculus\LazyModuleProcessor;
use Jaculus\LazyModuleProcessorStore;
use Jaculus\Module;

use PHPUnit\Framework\TestCase;

class LazyModuleProcessorStoreTest extends TestCase {

    function testPreservesKeys() {
        $module = new LazyModuleProcessor(new Module(), []);
        $store = new LazyModuleProcessorStore();
        $store->add('foo', $module);
        $this->assertEquals($module, $store->get('foo'));
    }

    function testThrowsOnInvalidKey() {
        $this->expectException(Jaculus\Exception::class);
        $store = new LazyModuleProcessorStore();
        $store->get('not_exists');
    }
}