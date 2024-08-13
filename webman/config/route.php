<?php

use Webman\Route;

Route::get('/test', [app\http\generate\controller\GenerateController::class, 'index']);


Route::disableDefaultRoute();
