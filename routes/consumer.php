<?php

use App\Http\Controllers\Common\QuestionController;
use App\Http\Controllers\Common\QuoteController;
use App\Http\Controllers\Consumer\AuthController;
use App\Http\Controllers\Consumer\BannerController;
use App\Http\Controllers\Consumer\InspirationController;
use App\Http\Controllers\Consumer\JobCategoryController;
use App\Http\Controllers\Consumer\JobCategoryMenuController;
use App\Http\Controllers\Consumer\SystemController;
use App\Http\Controllers\Consumer\UserAddressController;
use App\Http\Controllers\Consumer\UserCardController;
use App\Http\Controllers\Consumer\UserController;
use App\Http\Controllers\Consumer\UserCouponController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/common.php';

Route::group(['middleware' => ['api', 'set.locale'], 'prefix' => 'consumer'], function () {
    authRoutes(AuthController::class);
    smsRoutes();
    constantsRoutes(SystemController::class);
    Route::get('job-categories', [JobCategoryController::class, 'getCategoriesByMenuId']);
    Route::get('job-categories/hot', [JobCategoryController::class, 'getHotCategories']);
    Route::get('job-category-menus', [JobCategoryMenuController::class, 'index']);
    Route::get('job-category-menus/all', [JobCategoryMenuController::class, 'getAll']);
    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('banners', [BannerController::class, 'index']);
    Route::middleware('auth:api')->group(function () {
        uploadRoutes();
        userRoutes(UserController::class);
        Route::apiResource('user/quotes', QuoteController::class)->only('index', 'store', 'show', 'update');
        Route::apiResource('user/addresses', UserAddressController::class);
        Route::get('user/coupons', [UserCouponController::class, 'index']);
        Route::post('user/coupons/redeem', [UserCouponController::class, 'redeem']);
        Route::apiResource('user/cards', UserCardController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('user/cards/query', [UserCardController::class, 'queryCards']);
        Route::view('user/cards/bindCardPayToken', 'bindCardPayToken')->withoutMiddleware('auth:api'); //綠界專用輸入信用卡頁面
        Route::post('user/cards/bindCard', [UserCardController::class, 'bindCard']); //綠界專用綁定信用卡api，由 bindCardPayToken blade 訪問

        Route::group(['prefix' => 'inspirations'], function () {
            Route::get('design', [InspirationController::class, 'design']);
            Route::post('estimator', [InspirationController::class, 'estimator']);
            Route::get('interior-design', [InspirationController::class, 'interior-design']);
            Route::get('knowledge', [InspirationController::class, 'knowledge']);
        });
    });
});
