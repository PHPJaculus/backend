<?php

use PHPUnit\Framework\TestCase;
use Jaculus\SessionArrayWrapper;

class SessionArrayWrapperTest extends TestCase {

    public function testSessionNotStartedAfterConstruction() {
        $w = new SessionArrayWrapper();
        $this->assertFalse(self::hasStarted());
    }

    public function testSessionHasStartedAfterGet() {
        ob_start();
        $w = new SessionArrayWrapper();
        $w->foo;
        $this->assertTrue(self::hasStarted());
        ob_flush();
    }

    public static function hasStarted() {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}