<?php
namespace Jaculus;

class ModuleInstruction {
    private $name_v;
    private $input_v;

    public function __construct($name, $input) {
        $this->name_v = $name;
        $this->input_v = $input;
    }

    public function name() {
        return $this->name_v;
    }

    public function input() {
        return $this->input_v;
    }
}