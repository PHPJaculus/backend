<?php
namespace Jaculus;

class WantedIndiceValue {
    private $key_value;

    public function __construct($key_value) {
        $this->key_value = $key_value;
    }

    public function key() {
        return $this->key_value;
    }
}