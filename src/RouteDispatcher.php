<?php
namespace Jaculus;

class RouteDispatcher {
    private $router;
    private $not_found_r;
    private $method_not_allowed_r;
    private $server_error_r;
    private $maintenance_r;

    private $route_stack = [];
    private $module_stack = [];

    public function __construct() {
        $this->router = new \FastRoute\RouteCollector(
            new \FastRoute\RouteParser\Std(), new \FastRoute\DataGenerator\GroupCountBased() 
        );
    }

    public function route_group($base_route, Callable $adder) {
        $this->route_stack[] = $base_route;
        $adder($this);
        array_pop($this->route_stack);
    }

    public function module_group(array $modules, Callable $adder) {
        $this->module_stack[] = $modules;
        $adder($this);
        array_pop($this->module_stack);
    }

    public function get($route, $template_name, $modules = []) {
        $this->add('GET', $route, $modules, $template_name);
    }

    public function post($route, $template_name, $modules = []) {
        $this->add('POST', $route, $modules, $template_name);
    }

    public function not_found($template_name, $modules = []) {
        $modules = $this->concat_modules($modules);
        $this->not_found_r = self::create_handler($modules, $template_name);
    }

    public function method_not_allowed($template_name, $modules = []) {
        $modules = $this->concat_modules($modules);
        $this->method_not_allowed_r = self::create_handler($modules, $template_name);
    }

    public function server_error($template_name, $modules = []) {
        $modules = $this->concat_modules($modules);
        $this->server_error_r = self::create_handler($modules, $template_name);
    }

    public function maintenance($template_name, $modules = []) {
        $modules = $this->concat_modules($modules);
        $this->maintenance_r = self::create_handler($modules, $template_name);
    }

    public function get_not_found() {
        return $this->not_found_r;
    }

    public function get_method_not_allowed() {
        return $this->method_not_allowed_r;
    }

    public function get_server_error() {
        return $this->server_error_r;
    }

    public function get_maintenance() {
        return $this->maintenance_r;
    }

    public function dispatch($method, $url) {
        $dispatcher = new \FastRoute\Dispatcher\GroupCountBased($this->router->getData());
        return $dispatcher->dispatch($method, $url);
    }

    private function add($method, $route, $modules, $template_name) {
        $route = $this->concat_routes($route);
        $modules = $this->concat_modules($modules);
        $this->router->addRoute($method, $route, self::create_handler($modules, $template_name));
    }

    private function concat_routes($top_route) {
        $result = "";
        foreach($this->route_stack as $route_part) {
            $result .= $route_part;
        }
        $result .= $top_route;
        return $result;
    }

    private function concat_modules($top_modules) {
        $modules = [];
        foreach($this->module_stack as $module_array) {
            $modules = array_merge($modules, $module_array);
        }
        $modules = array_merge($modules, $top_modules);
        return $modules;
    }

    private static function create_handler($modules, $template_name) {
        return function() use($modules, $template_name) {
            return new DispatchResult($template_name, $modules);
        };
    }
}