<?php

use App\Http\Controllers\Pro\AuthController;
use App\Http\Controllers\Pro\SystemController;
use App\Http\Controllers\Pro\UserController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/common.php';

Route::group(['middleware' => ['api', 'set.locale'], 'prefix' => 'pro'], function () {
    authRoutes(AuthController::class);
    smsRoutes();
    constantsRoutes(SystemController::class);
    Route::middleware('auth:api')->group(function () {
        uploadRoutes();
        userRoutes(UserController::class);
    });
});
