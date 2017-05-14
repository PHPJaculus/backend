<?php
namespace Jaculus;

class Request {
    public $method;
    public $uri;

    public $uri_vars; //Initialize in Jaculus framework itself
    public $get;
    public $post;
    public $files;
    public $cookies;
    public $session;
    public $request;

    public function __construct() {
        $this->method      = $_SERVER['REQUEST_METHOD'];
        $this->uri         = $_SERVER['REQUEST_URI'];
        $this->get         = DI::get('$_GET');
        $this->post        = DI::get('$_POST');
        $this->files       = DI::get('$_FILES');
        $this->cookies     = DI::get('$_COOKIE');
        $this->session     = DI::get('$_SESSION');
        $this->request     = DI::get('$_REQUEST');
    }

    public function redirect($uri) {
        header('Location: ' . $uri);
    }

    public function content_type($str) {
        header('Content-Type: ' . $str);
    }
}