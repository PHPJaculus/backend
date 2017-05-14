<?php

use Jaculus\ErrorHandlerStore;
use PHPUnit\Framework\TestCase;

class ErrorHandlerStoreTest extends TestCase {
    /*
    function testFatal() {
        $store = new ErrorHandlerStore();
        $handler = function() {};
        $store->fatal_error_handler($handler);
        $store->setup_fatal_error_handler();
        //No assert since there are no way to get it out of PHP itself
    }
    */

    function testException() {
        $store = new ErrorHandlerStore();
        $handler = function() {};
        $store->exception_error_handler($handler);
        $this->assertEquals($store->get_exception_error_handler(), $handler);
    }
}