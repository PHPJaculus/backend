<?php

use Jaculus\UserPermissions;
use PHPUnit\Framework\TestCase;

class UserPermissionsTests extends TestCase  {
    private $i;

    public function setUp() {
        $this->i = new UserPermissions(['guest', 'user']);
        $this->i->setCurrent('guest');
    }

    public function testLowestIsDefault() {
        $this->assertEquals($this->i::getCurrent(), 0);
        $this->assertEquals($this->i->valueOf('guest'), 0);
    }

    public function testCurrentIsStatic() {
        $this->i->setCurrent('user');
        $this->assertEquals($this->i::getCurrent(), $this->i->valueOf('user'));
    }

    public function testAllNamesIsAllNames() {
        $this->assertEquals($this->i->allNames(), ['guest', 'user']);
    }

    public function testValuesAreHierarchal() {
        $gv = $this->i->valueOf('guest');
        $uv = $this->i->valueOf('user');

        $this->assertGreaterThan($gv, $uv);
    }
}