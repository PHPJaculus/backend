<?php
namespace Jaculus;

class LazyModuleProcessorStore {
    private $lazy_modules = [];

    public function add($output_key, LazyModuleProcessor $module) {
        $this->lazy_modules[$output_key] = $module;
    }

    public function has($output_key) {
        return array_key_exists($output_key, $this->lazy_modules);
    }

    public function get($output_key) {
        if(!isset($this->lazy_modules[$output_key]))
            throw new Exception("Module which outputs in key \"$output_key\" is not defined.");
        return $this->lazy_modules[$output_key];
    }
}