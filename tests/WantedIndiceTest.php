<?php

use Jaculus\WantedIndicesStore;
use Jaculus\WantedIndiceValue;

use PHPUnit\Framework\TestCase;

class WantedIndiceTest extends TestCase  {

    public function testNoReplacement() {
        $wanted = new WantedIndicesStore();
        $values = [['foo' => 'bar']];
        $target_array = [];
        
        $wanted->replaceWantedIndices($values, $target_array);
        $this->assertEquals([], $target_array);
    }

    public function testReplaceNotArrayTarget() {
        $wanted = new WantedIndicesStore();
        $values = [['foo' => 'bar']];
        $target = $wanted->foo;
        
        $wanted->replaceWantedIndices($values, $target);
        $this->assertEquals('bar', $target);
    }

    public function testReplace1D() {
        $wanted = new WantedIndicesStore();
        $values = [['foo' => 'bar']];
        $target_array = ['foo' => $wanted->foo];

        $wanted->replaceWantedIndices($values, $target_array);
        $this->assertEquals(['foo' => 'bar'], $target_array);
    }

    public function testReplaceMultiD() {
        $wanted = new WantedIndicesStore();
        $values = [['foo' => 42, 'bar' => 89]];
        $target_array = ['foo' => $wanted->foo, 'bar' => ['zebra' => $wanted->bar]];

        $wanted->replaceWantedIndices($values, $target_array);
        $this->assertEquals([
            'foo' => 42,
            'bar' => ['zebra' => 89]
        ], $target_array);
    }
}