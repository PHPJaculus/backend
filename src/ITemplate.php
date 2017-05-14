<?php
namespace Jaculus;

interface ITemplate {
    function name();
    function makeTwig(UserPermissions $user_permissions);
}