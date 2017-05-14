<?php
namespace Jaculus;

use Jaculus\ArrayWrapper;

class SessionArrayWrapper extends ArrayWrapper {

    public function __construct() {
        parent::__construct([]);
    }

    protected function get_impl($name) {
        if(session_status() !== PHP_SESSION_ACTIVE)
            session_start();
        return parent::get_impl($name);
    }
}