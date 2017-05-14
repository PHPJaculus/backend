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
    public $db;
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
        $this->db           = function() { return DI::get(DB::class); };
		$this->permissions  = DI::get(UserPermissions::class);
    }

    public function process(array $input) {
        return null;
    }

    public function install() {

    }

    public static function processModules(array $mod_instructions, array $mods) {
        $result = [];
    
        foreach($mod_instructions as $variable => $instruction) {
            if(!$instruction instanceof ModuleInstruction)
                throw new Exception("A module instruction is not an instance of ModuleInstruction");

            $name = $instruction->name();
            if(!isset($mods[$name]))
                throw new Exception("There are no module named $name");
            
            $module = $mods[$name];
            if(!($module instanceof Module))
                throw new Exception("Module with name $module is not an instance of class Module");
            
            $input = $instruction->input();
            $output = $module->process($input);
            
            if(!is_null($output)) {
                if(!is_string($variable))
                    throw new Exception("output key of module with name $name is not defined even though it spites out output");
                
                if(array_key_exists($variable, $result))
                    throw new Exception("Output variable \"$output\" is defined several times");
                $result[$variable] = $output;
            }
        }
        
        return $result;
    }
}