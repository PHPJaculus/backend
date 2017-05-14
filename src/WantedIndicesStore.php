<?php
namespace Jaculus;

class WantedIndicesStore {
    public function __get($name) {
        return new WantedIndiceValue($name);
    }

    public function replaceWantedIndices(array $values, &$target) {
        if(is_array($target)) {
            foreach($target as $k => &$v) {
                if(is_array($v))
                    $this->replaceWantedIndices($values, $v);
                else {
                    $this->replaceSingleWantedIndice($values, $v);
                }
            }
        } else {
            $this->replaceSingleWantedIndice($values, $target);
        }
    }

    private function replaceSingleWantedIndice(array $values, &$target) {
        if($target instanceof WantedIndiceValue) {
            $indice_key = $target->key();
            foreach($values as $value_array) {
                if(array_key_exists($indice_key, $value_array)) {
                    $target = $value_array[$indice_key];
                    return;
                }
            }
            $target = null;
        }
    }
}