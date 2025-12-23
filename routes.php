<?php

use App\Controller\Module1Controller;
use App\Controller\Module2Controller;
use App\Controller\RootController;
use Router\Route;

Route::get('/', RootController::class);
Route::post('/module1', Module1Controller::class, 'save');
Route::get('/module1', Module1Controller::class);
Route::get('/module1/action1/', Module1Controller::class, 'action1');
Route::get('/module1/action1/{param1}', Module1Controller::class, 'action1');
Route::get('/module2/{param1}/param2/{param2}', Module2Controller::class)->name('m2');
Route::get('/module2/action1/{param1}/', Module2Controller::class, 'action1');