<?php
namespace Jaculus;

class ErrorHandlerStore {
    private $fatal_error_handler;
    private $exception_error_handler;
    
    public function fatal_error_handler(Callable $fun) {
        $this->fatal_error_handler = $fun;
    }

    public function exception_error_handler(Callable $fun) {
        $this->exception_error_handler = $fun;
    }

    public function get_exception_error_handler() {
        return $this->exception_error_handler;
    }

    public function run_exception_handler(\Exception $exc) {
        $h = $this->exception_error_handler;
        if($h)
        {
            $h($exc);
        }
    }

    public function setup_fatal_error_handler() {
        $h = $this->fatal_error_handler;
        if($h) {
            set_error_handler($h);
        }
    }
}