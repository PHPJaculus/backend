<?php
namespace Jaculus\TwigNode;

final class Permission extends \Twig_Node
{
    static $has_generated_init = false;

    public function __construct($permission_value, \Twig_Node $body, $lineno, $tag = null)
    {
        parent::__construct(
            array(
                'body' => $body
            ), 
            array(
                'permission_value' => $permission_value
            ), 
            $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        if(!self::$has_generated_init) {
            self::$has_generated_init = true;
            $compiler->write('$jaculus_permission_value = Jaculus\\UserPermissions::getCurrent();' . "\n");
        }

        $compiler
            ->write(sprintf("if(\$jaculus_permission_value >= %d) {\n", $this->getAttribute('permission_value')))
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("}\n\n")
        ;
    }

    public static function reset() {
        self::$has_generated_init = false;
    }
}