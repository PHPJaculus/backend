<?php
namespace Jaculus;

interface IModule {
    public function validate_input(ArrayWrapper $input);
    public function process(ArrayWrapper $input);
    public function install();
}