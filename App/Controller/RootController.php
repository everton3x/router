<?php

namespace App\Controller;

use Router\Route;

class RootController
{
    public function index(): void
    {
        echo self::class;
        echo '<br>';
        echo Route::$currentUrl;
    }
}