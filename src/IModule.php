<?php
namespace Jaculus;

interface IModule {
    public function process(array $input);
    public function install();
}