<?php

namespace App\Controller;

use Router\Route;

class Module2Controller
{
    public function index(int $param1, string $param2): void
    {
        echo __CLASS__;
        echo __METHOD__;
        echo PHP_EOL;
        var_dump($param1);
        echo PHP_EOL;
        var_dump($param2);
        echo PHP_EOL;
        echo Route::url('m2', [
            'param1' => $param1,
            'param2' => $param2,
        ]);
    }

    public function action1(): void
    {
        echo __CLASS__;
        echo __METHOD__;
    }
}