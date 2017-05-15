<?php
namespace Jaculus;

class Module implements IModule {
    //Module behavior
    public $lazy = true;

    //Super variables
    public $globals;
    public $server;
    public $env;

    //Other
    public $request;
    public $config;
	public $permissions;

    public function beforeProcessing() {
        $this->request  = DI::get(Request::class);
        $this->globals  = DI::get('$GLOBALS');
        $this->server   = DI::get('$_SERVER');
        $this->env      = DI::get('$_ENV');

        $this->config   = function($id) { 
            try {
                return DI::get($id);
             } catch(\Exception $e) {
                 return null;
             } 
        };
		$this->permissions  = DI::get(UserPermissions::class);
    }

    public function db() {
        return DI::get(DB::class);
    }

    public function validate_input(ArrayWrapper $input) {

    }

    public function process(ArrayWrapper $input) {
        return null;
    }

    public function install() {

    }
}