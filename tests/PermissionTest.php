<?php

use Jaculus\UserPermissions;
use Jaculus\TwigTokenParser\Permission;
use Jaculus\TwigNode\Permission as NodePermission;
use PHPUnit\Framework\TestCase;

class PermissionTests extends TestCase  {

    public function testToLowPermission() {
        $twig = $this->makeTwig('guest', '{% permission user %} content {% endpermission %}');
        $text = $twig->render('template');
        $this->assertNotContains('content', $text);

        $twig = $this->makeTwig('guest', '{% permission user "content" %}');
        $text = $twig->render('template');
        $this->assertNotContains('content', $text);
    }

    public function testEqualOrHigherPermission() {
        $twig = $this->makeTwig('user', '{% permission user %} content {% endpermission %}');
        $text = $twig->render('template');
        $this->assertContains('content', $text);

        $twig = $this->makeTwig('user', '{% permission user "content" %}');
        $text = $twig->render('template');
        $this->assertContains('content', $text);
    }

    private function makeTwig($current_permission, $template_text) {
        NodePermission::reset();
        $permissions = new UserPermissions(['guest', 'user']);
        $permissions->setCurrent($current_permission);

        $twig_loader = new Twig_Loader_Array(['template' => $template_text]);
        $twig = new Twig_Environment($twig_loader);

        $twig->addTokenParser(new Permission($permissions));
        return $twig;
    }
}