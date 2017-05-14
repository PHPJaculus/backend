<?php
namespace Jaculus;


class ModuleHelper {
    
    public function __call($name, array $args) {
        if(count($args) === 1) {
            foreach($args as $k => $v) {
                if(is_array($v))
                    $args = $args[$k];
                break;
            }
        }
        return new ModuleInstruction($name, $args);
    }
}