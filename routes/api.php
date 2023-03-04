<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

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
Route::post('register', [AuthController::class, 'register'])->middleware('log.route');
Route::post('login', [AuthController::class, 'login'])->middleware('log.route');

Route::middleware('auth:api')->group(function(){
    Route::get('logout',[AuthController::class, 'logout']);
    Route::get('user',[AuthController::class,'user']);

    Route::get('productcat/{slug}',[CategoryController::class, 'productCat']);
    Route::get('cat',[CategoryController::class, 'display']);

    //Route::get('/products/{category}', 'ProductController@cat');

    Route::get('/products',[ProductController::class, 'display']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
});

