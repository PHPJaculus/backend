<?php

use Jaculus\ModuleListBuilder;

use Jaculus\Module;

use PHPUnit\Framework\TestCase;

class ModuleListBuilderTest extends TestCase {

    public function testEmpty() {
        $l = new ModuleListBuilder();
        $this->assertCount(0, $l->getAll());
    }

    public function testStores() {
        $l = new ModuleListBuilder();
        $l->add('foo', new Module());
        $this->assertCount(1, $l->getAll());
    }
}