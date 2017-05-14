<?php
namespace Jaculus;

class LazyModuleProcessor implements \ArrayAccess {
    private $value = null;
    private $has_executed = false;
    private $module;
    private $input;

    public function __construct(IModule $module, array $input) {
        $this->module = $module;
        if(count($input) > 0)
        {
            $this->input = new ArrayWrapper($input);
        }
    }

    public function __call($name, array $args) {
        $this->run_if_necessary();
        return call_user_func_array([$this->value, $name], $args);
    }

    public function __get($name) {
        $this->run_if_necessary();
        return $this->value->$name;
    }

    public function __set($name, $value) {
        $this->run_if_necessary();
        $this->value->$name = $value;
    }

    public function __isset($name) {
        $this->run_if_necessary();
        return isset($this->value->$name);
    }

    public function __unset($name) {
        $this->run_if_necessary();
        unset($this->value->$name);
    }

    public function __toString() {
        $this->run_if_necessary();
        return (string)$this->value;
    }

    public function __invoke(array $args) {
        $this->run_if_necessary();
        return call_user_func_array($this->value, $args);
    }

    public function offsetExists ($offset) {
        $this->run_if_necessary();
        return array_key_exists($offset, $this->value);
    }

    public function offsetGet ($offset) {
        $this->run_if_necessary();
        return $this->value[$offset];
    }

    public function offsetSet ($offset, $value) {
        $this->run_if_necessary();
        $this->value[$offset] = $value;
    }

    public function offsetUnset ($offset) {
        $this->run_if_necessary();
        unset($this->value[$offset]);
    }

    private function run_if_necessary() {
        if(!$this->has_executed) {
            $this->has_executed = true;
            $this->module->beforeProcessing();
            if(!$this->input)
                $this->input = new ArrayWrapper();

            $this->value = $this->module->process($this->input);
        }
    }
}