<?php

use Webman\Route;

Route::post('/http/generate/test', [app\http\generate\controller\TestController::class, 'index']);


Route::disableDefaultRoute();
