<?php

namespace App\Controller;

class Module1Controller
{
    public function index(): void
    {
        echo __CLASS__;
        echo __METHOD__;
        echo PHP_EOL;
        echo 'GET';
        echo PHP_EOL;
        var_dump($_SERVER['REQUEST_METHOD']);
    }
    
    public function save(): void
    {
        echo __CLASS__;
        echo __METHOD__;
        echo PHP_EOL;
        echo 'POST';
        echo PHP_EOL;
        var_dump($_SERVER['REQUEST_METHOD']);
    }

    public function action1(?int $param1 = null): void
    {
        echo __CLASS__;
        echo __METHOD__;
        echo PHP_EOL;
        var_dump($param1);
    }
}