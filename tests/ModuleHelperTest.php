<?php

use Jaculus\ModuleHelper;
use Jaculus\ModuleInstruction;

use PHPUnit\Framework\TestCase;

class ModuleHelperTests extends TestCase  {
    public function testCall() {
        $helper = new ModuleHelper();
        $module = $helper->module(1, 2, 3);

        $this->assertEquals(new ModuleInstruction('module', [1,2,3]), $module);
    }

    public function testModuleHelperTurnsSingleInto1DArray() {
        $helper = new ModuleHelper();
        $module = $helper->module(['foo' => 'bar']);
        $this->assertEquals([
            'foo' => 'bar'
        ], $module->input());
    }
}