<?php

use Jaculus\ModuleHelper;
use Jaculus\Module;
use Jaculus\DI;
use Jaculus\ArrayWrapper;
use Jaculus\Request;
use Jaculus\UserPermissions;

use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase  {

    public function testProcessModules() {
        $mh = new ModuleHelper();
        $modules = ["module1" => new Module1()];
        $instructions = [
            "var" => $mh->module1(1,2,3)
        ];
        $vars = Module::processModules($instructions, $modules);

        $this->assertEquals([
            'var' => [1,2,3]
        ], $vars);
    }

    public function testVars() {
        DI::setupJaculus(null, []);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';
        try {
            $m = new Module();
            $m->beforeProcessing();
            
            $this->assertInstanceOf(ArrayWrapper::class, $m->globals);
            $this->assertInstanceOf(ArrayWrapper::class, $m->server);
            $this->assertInstanceOf(ArrayWrapper::class, $m->env);

            $this->assertInstanceOf(Request::class, $m->request);
            //$this->assertIsCallable($m->config); TODO: This requires a .env file
            //                                              And setupJaculus can not be called with null as first arg then
            $this->assertIsCallable($m->db);
            $this->assertInstanceOf(UserPermissions::class, $m->permissions);

        } finally {
            DI::destroy();
        }
    }

    private function assertIsCallable(Callable $fn) {} //Will cause an error if the value is not callable
}

class Module1 extends Module {
    public function process(array $input) {
        return $input;
    }
}