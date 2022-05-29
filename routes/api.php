<?php

use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\Product_I18NController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::post('merchant', [MerchantController::class, 'save']);
        Route::resource('products', CartController::class);
        Route::resource('products.i18n', Product_I18NController::class);


        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart', [CartController::class, 'addToCart']);
        Route::delete('cart', [CartController::class, 'destroy']);


    });
});

