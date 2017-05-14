<?php
namespace Jaculus\TwigTokenParser;

use \Jaculus\LazyModuleLoaderGenerator;

class LazyModuleEmit extends \Twig_TokenParser {
    private $generator;

    public function __construct(LazyModuleLoaderGenerator $generator) {
        $this->generator = $generator;
    }

    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new \Jaculus\TwigNode\LazyModuleEmit($this->generator, $lineno, $this->getTag());
    }

    public function getTag() {
        return 'jaculus_lazy_module_emit';
    }
}