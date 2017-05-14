<?php

namespace Jaculus;

use \DI\ContainerBuilder;
use \DI\Container;

function makeTestDI(array $permissions, array $module_list, callable $twig_loader_factory = null) {
    TwigNode\Permission::reset();
    $c = new ContainerBuilder();
    $c->addDefinitions([
        'Permissions' => function(Container $c) use($permissions) { return $permissions; },
        'ModuleList' => function(Container $c) use($module_list) { return $module_list; },

        \Twig_LoaderInterface::class => $twig_loader_factory ? $twig_loader_factory : function(Container $c) { return new \Twig_Loader_Array(['index' => 'foo_text']); },
        \Twig_Environment::class => function(Container $c) {
            return new \Twig_Environment($c->get(\Twig_LoaderInterface::class));
        },
        UserPermissions::class => function(Container $c) {
            return new UserPermissions($c->get('Permissions'));
        },
        RouteDispatcher::class => function(Container $c) {
            return new RouteDispatcher($c->get(UserPermissions::class), $c->get('ModuleList'));
        }
    ]);
    return $c;
}