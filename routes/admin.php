<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\JobCategoryController;
use App\Http\Controllers\Admin\JobCategoryMenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\QuestionGroupController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SmsLogController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Common\QuestionController;
use App\Http\Controllers\Common\QuoteController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/common.php';

Route::group(['middleware' => ['api', 'set.locale'], 'prefix' => 'admin'], function () {
    smsRoutes();
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('email', 'loginByEmail');
        Route::middleware(['auth:admin'])->group(function () {
            Route::get('user-info', 'userInfo');
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
        });
    });
    Route::middleware(['auth:admin'])->group(function () {
        uploadRoutes();
        Route::middleware('permission')->group(function () {
            Route::apiResource('job-category-menus', JobCategoryMenuController::class)->only(['index', 'show', 'update']);
            Route::apiResource('job-categories', JobCategoryController::class)->only(['index', 'show', 'store', 'update']);
            Route::apiResource('admin-users', AdminUserController::class);
            Route::apiResource('users', UserController::class);
            Route::apiResource('roles', RoleController::class);
            Route::apiResource('permissions', PermissionController::class);
            Route::apiResource('quotes', QuoteController::class);
            Route::apiResource('questions', QuestionController::class);
            Route::get('question-categories', [QuestionController::class, 'getQuestionCategories']);
            Route::apiResource('question-groups', QuestionGroupController::class);
            Route::apiResource('tags', TagController::class);
            Route::post('sms-logs/filter', [SmsLogController::class, 'filter']);
            Route::apiResource('banners', BannerController::class);
            Route::apiResource('features', FeatureController::class)->only(['index']);
            Route::apiResource('coupons', CouponController::class);
        });
    });
});
