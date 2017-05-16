<?php

return [
    'db' => [
        'database_type' => 'mysql',
        'database_name' => 'basic_modules',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'Bergvalls1',
        'charset' => 'utf8',
        'port' => 3306,
        'prefix' => '',
        'option' => [
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ]
];