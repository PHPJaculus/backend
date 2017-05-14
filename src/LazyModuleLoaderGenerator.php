<?php
namespace Jaculus;

class LazyModuleLoaderGenerator {
    private $code = '';

    public function addModule($output_key) {
        $this->code .= "\$context['" . $output_key . '\'] = Jaculus\\DI::get(Jaculus\\LazyModuleProcessorStore::class)->get(\'' . $output_key . "');\n";
    }

    public function generate() {
        return $this->code . "\n";
    }
}