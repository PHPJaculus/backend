<?php
namespace Jaculus;

class ArrayWrapper implements \ArrayAccess {
    private $array;

    public function __construct(array $array) {
        $this->array = $array;
    }

    public function __get($name) {
        return $this->get_impl($name);
    }

    public function offsetExists ($offset) {
        return array_key_exists($offset, $this->array);
    }

    public function offsetGet ($offset) {
        return $this->get_impl($offset);
    }

    public function offsetSet ($offset, $value) {
        self::invalid_op($offset);
    }

    public function offsetUnset ($offset) {
        self::invalid_op($offset);
    }

    protected function get_impl($name) {
        if(!array_key_exists($name, $this->array))
            return null;
        return $this->array[$name];
    }

    private static function invalid_op($offset) {
        throw new Exception("Can not set index $offset. ArrayWrapper is const.");
    }
}