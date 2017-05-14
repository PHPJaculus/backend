<?php
namespace Jaculus\TwigNode;

use Jaculus\LazyModuleLoaderGenerator;

final class LazyModuleEmit extends \Twig_Node
{
    private $generator;

    public function __construct(LazyModuleLoaderGenerator $generator, $lineno, $tag = null) {
        $this->generator = $generator;
        parent::__construct([], [], $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write($this->generator->generate())
        ;
    }
}