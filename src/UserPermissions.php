<?php
namespace Jaculus;

final class UserPermissions {
    private $permission_names = [];
    private static $permission = 0;

    public function __construct(array $names) {
        $this->permission_names = $names;
    }

    public static function getCurrent() {
        return self::$permission;
    }
	
	public function getCurrentName() {
		return $this->permission_names[self::$permission];
	}

    public function setCurrent($name) {
        $v = $this->valueOf($name);
        if($v === FALSE)
            throw new Exception("There are no permission named $name");
        self::$permission = $v;
    }

    public function allNames() {
        return $this->permission_names;
    }

    public function valueOf($name) {
        return array_search($name, $this->permission_names);
    }
}