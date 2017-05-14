<?php

use Jaculus\RouteDispatcher;
use Jaculus\UserPermissions;
use Jaculus\Template\TextTemplate;
use Jaculus\DI;
use Jaculus\ModuleHelper;
use Jaculus\TwigNode\Permission as NodePermissions;

use DI\ContainerBuilder;
use DI\Container;

use FastRoute\Dispatcher as FastD;

use PHPUnit\Framework\TestCase;

class RouteDispatcherTest extends TestCase {

    public function testGetFound() {
        $d = $this->makeDispatcher('GET');
        $this->assertEquals($d->dispatch('GET', '/index')[0], FastD::FOUND);
    }

    public function testGetNotFound() {
        $d = $this->makeDispatcher('GET');
        $this->assertEquals($d->dispatch('GET', '/not_exists')[0], FastD::NOT_FOUND);
    }

    public function testPostFound() {
        $d = $this->makeDispatcher('POST');
        $this->assertEquals($d->dispatch('POST', '/index')[0], FastD::FOUND);
    }

    public function testPostNotFound() {
        $d = $this->makeDispatcher('POST');
        $this->assertEquals($d->dispatch('POST', '/not_exists')[0], FastD::NOT_FOUND);
    }

    public function testAddRouteGroup() {
        $dispatcher = new RouteDispatcher();
        $dispatcher->route_group('/group', function(RouteDispatcher $r) {
            $r->get('/index', '/index');

            $r->route_group('/group2', function(RouteDispatcher $r) {
                $r->get('/index', '/index');
            });
        });

        $result = $dispatcher->dispatch('GET', '/group/index');
        $this->assertEquals(FastD::FOUND, $result[0]);

        $result = $dispatcher->dispatch('GET', '/group/group2/index');
        $this->assertEquals(FastD::FOUND, $result[0]);
    }

    public function testAddModuleGroup() {
        $dispatcher = new RouteDispatcher();
        $mh = new ModuleHelper();

        $dispatcher->module_group([
            $mh->random_module()
        ], function(RouteDispatcher $r) use($mh) {
            $r->get('/index', '/index');

            $r->module_group([
                $mh->random_module_2()
            ], function(RouteDispatcher $r) {
                $r->get('/index2', '/index2');
            });
        });

        $dispatcher->get('/index3', '/index3');

        $result = $dispatcher->dispatch('GET', '/index');
        $this->assertEquals(FastD::FOUND, $result[0]);
        
        $module_instructions = $result[1]()->moduleInstructions();
        $this->assertEquals(1, count($module_instructions));
        $this->assertEquals('random_module', $module_instructions[0]->name());

        $result = $dispatcher->dispatch('GET', '/index2');
        $this->assertEquals(FastD::FOUND, $result[0]);
        
        $module_instructions = $result[1]()->moduleInstructions();
        $this->assertEquals(2, count($module_instructions));
        $this->assertEquals('random_module', $module_instructions[0]->name());
        $this->assertEquals('random_module_2', $module_instructions[1]->name());

        $result = $dispatcher->dispatch('GET', '/index3');
        $this->assertEquals(FastD::FOUND, $result[0]);
        
        $module_instructions = $result[1]()->moduleInstructions();
        $this->assertEquals(0, count($module_instructions));
    }

    public function testRender() {
        self::setupDI();
        $dispatcher = $this->makeDispatcher('GET');
        $dispatch = $dispatcher->dispatch('GET', '/index');
        $this->assertEquals($dispatch[0], FastRoute\Dispatcher::FOUND);
        
        $dispatch_result = $dispatch[1]();
        $twig = DI::get(Twig_Environment::class);
        $text = $twig->render($dispatch_result->templateName(), []);
        $this->assertEquals('content', $text);
    }

    private function setupDI() {
        $c = new ContainerBuilder();
        $c->addDefinitions([
            Twig_LoaderInterface::class => function(Container $c) { 
                return new Twig_Loader_Array(['/index' => 'content']);
            },
            Twig_Environment::class => function(Container $c) {
                return new Twig_Environment($c->get(Twig_LoaderInterface::class));
            }
        ]);
        DI::setContainer($c->build());
    }

    private function makeDispatcher($method) {
        $dispatcher = new RouteDispatcher();
        call_user_func([$dispatcher, $method], '/index', '/index', []);
        return $dispatcher;
    }
}