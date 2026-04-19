<?php

use App\Http\Controllers\Common\CardCallbackController;
use App\Http\Controllers\Common\SmsCallbackController;
use App\Http\Controllers\Common\SmsController;
use App\Http\Controllers\Common\UploadController;
use Illuminate\Support\Facades\Route;

//所有直接使用的路由均放在此群組內，如此方能正確套用中間件。
Route::middleware(['api', 'set.locale'])->group(function () {
    Route::prefix('callbacks')->group(function () {
        Route::match(['get', 'post'], 'sms/{provider_id}', [SmsCallbackController::class, 'handleCallback'])->name('callbacks.sms');
        Route::post('card/{provider_id}', [CardCallbackController::class, 'handleCallback'])->name('callbacks.card');
    });
});

//以下是被引用的路由方法。
if (!function_exists('authRoutes')) {
    function authRoutes($controllerClass): void
    {
        Route::controller($controllerClass)->group(function () {
            Route::get('phones/check', 'checkPhone');
            Route::get('emails/check', 'checkEmail');
            Route::prefix('auth')->group(function () {
                Route::post('phone', 'loginByPhone');
                Route::post('email', 'loginByEmail');
                Route::post('register', 'register');
                Route::middleware('auth:api')->group(function () {
                    Route::post('logout', 'logout');
                    Route::post('refresh', 'refresh');
                });
            });
        });
    }
}

if (!function_exists('userRoutes')) {
    function userRoutes($controllerClass): void
    {
        Route::controller($controllerClass)->group(function () {
            Route::get('user/profile', 'getProfile');
            Route::get('user/profile/brief', 'getProfileBrief');
            Route::get('user/notification-setting', 'getNotificationSetting');
            Route::put('user/profile', 'setProfile');
            Route::put('user/password', 'setPassword');
            Route::put('user/notification-setting', 'setNotificationSetting');
            Route::delete('user', 'remove');
        });
    }
}

if (!function_exists('smsRoutes')) {
    function smsRoutes(): void
    {
        Route::controller(SmsController::class)->group(function () {
            Route::post('sms', 'sendSms');
        });
    }
}

if (!function_exists('constantsRoutes')) {
    function constantsRoutes($controllerClass): void
    {
        Route::controller($controllerClass)->group(function () {
            Route::get('maintenance', 'getMaintenanceStatus');
            Route::prefix('constants')->group(function () {
                Route::get('version', 'getConstantsVersion');
                Route::get('app-version', 'getAppVersion');
                Route::get('app-market', 'getAppMarket');
                Route::get('frontend', 'getFrontendConstants');
            });
        });
    }
}

if (!function_exists('uploadRoutes')) {
    function uploadRoutes(): void
    {
        Route::controller(UploadController::class)->group(function () {
            Route::post('images', 'uploadImage');
            Route::post('videos', 'uploadVideo');
        });
    }
}
