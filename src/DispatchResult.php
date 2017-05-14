<?php
namespace Jaculus;

class DispatchResult {
    private $template_name_v;
    private $module_instructions_v;
    
    public function __construct($template_name, array $module_instructions) {
        $this->template_name_v = $template_name;
        $this->module_instructions_v = $module_instructions;
    }

    public function templateName() {
        return $this->template_name_v;
    }

    public function moduleInstructions() {
        return $this->module_instructions_v;
    }
}