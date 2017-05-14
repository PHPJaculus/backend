<?php
namespace Jaculus\TwigTokenParser;

use Jaculus\TwigNode as Node;
use Jaculus as PK;

final class Permission extends \Twig_TokenParser
{
    private $permissions;

    public function __construct(PK\UserPermissions $permissions) {
        $this->permissions = $permissions;
    }

    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();
        $permission_value = $this->permissions->valueOf($name);
        if($permission_value === FALSE)
            throw new \Twig_Error_Syntax("There are no permission named $name");

        $block = new \Twig_Node_Block($name, new \Twig_Node(array()), $lineno);
        $this->parser->setBlock($name, $block, $lineno);
        $this->parser->pushLocalScope();

        if($stream->nextIf(\Twig_Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(
                [$this, 'decideBlockEnd']
                , true
            );

            if ($token = $stream->nextIf(\Twig_Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name)
                    throw new \Twig_Error_Syntax(sprintf('Expected endpermission for block "%s" (but "%s" given).', $name, $value), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        } else {
            $body = new \Twig_Node(array(
                new \Twig_Node_Print($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ));
        }
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        
        $block->setNode('body', $body);
        $this->parser->popBlockStack();
        $this->parser->popLocalScope();
        
        return new Node\Permission($permission_value, $body, $lineno, $this->getTag());
    }

    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('endpermission');
    }

    public function getTag()
    {
        return 'permission';
    }
}