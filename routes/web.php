<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/common.php';

Route::group(['middleware' => ['api', 'set.locale'], 'prefix' => 'web'], function () {
    authRoutes(AuthController::class);
    smsRoutes();
    Route::middleware('auth:api')->group(function () {
        uploadRoutes();
        userRoutes(UserController::class);
    });
});

Route::get('chat', function () {
    return view('chat');
});
