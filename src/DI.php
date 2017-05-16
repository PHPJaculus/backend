<?php
namespace Jaculus;

use \DI\ContainerBuilder;
use \DI\Container;


class DI {
    private static $di;

    public static function destroy() {
        self::$di = null;
    }

    public static function get($id) {
        try {
            self::$di->get($id);
        } catch(\Exception $e) {
            return null;
        }
    }

    public static function setContainer(Container $container) {
        self::$di = $container;
    }

    public static function setupJaculus($dir, array $permissions) {
        $di_builder = new ContainerBuilder();
        if($dir !== null)
            $di_builder->addDefinitions($dir . '/env.php');

        $aw = function(array &$arr) {
            return function(Container $c) use(&$arr) { return new ArrayWrapper($arr); };
        };
        $di_builder->addDefinitions([
            '$GLOBALS' => $aw($GLOBALS),
            '$_SERVER' => $aw($_SERVER),
            '$_GET' => $aw($_GET),
            '$_POST' => $aw($_POST),
            '$_FILES' => $aw($_FILES),
            '$_COOKIE' => $aw($_COOKIE),
            '$_SESSION' => function(Container $c) { return new SessionArrayWrapper(); },
            '$_REQUEST' => $aw($_REQUEST),
            '$_ENV' => $aw($_ENV),

            \Twig_LoaderInterface::class => function(Container $c) {
                return $c->get(FileTemplateLoader::class);
            },
            FileTemplateLoader::class => function(Container $c) use($dir) {
                return new FileTemplateLoader($dir . '/app/templates');
            },
            \Twig_Environment::class => function(Container $c) use($dir) {
                $permissions = $c->get(UserPermissions::class);
                $config = !$c->get('app.cache') ? [] : [
                    'cache' => $dir . '/cache/twig'
                ];

                $twig = new \Twig_Environment($c->get(\Twig_LoaderInterface::class), $config);
                $twig->addTokenParser(new TwigTokenParser\Permission($permissions));
                $twig->addTokenParser(new TwigTokenParser\LazyModuleEmit($c->get(LazyModuleLoaderGenerator::class)));
                return $twig;
            },
            UserPermissions::class => function(Container $c) use($permissions) {
                return new UserPermissions($permissions);
            },
            DB::class => function(Container $c) {
                return new DB([
                    'database_type' => DI::get('db.type'),
                    'database_name' => DI::get('db.name'),
                    'server' => DI::get('db.server'),
                    'username' => DI::get('db.username'),
                    'password' => DI::get('db.password'),
                    'charset' => DI::get('db.charset'),
                    'port' => DI::get('db.port'),
                    'prefix' => DI::get('db.prefix'),
                    'option' => DI::get('db.driver_option')
                ]);
            }
        ]);
        DI::setContainer($di_builder->build());
    }
}