<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
//use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    // Category
    Route::resource('/category',CategoryController::class);
    Route::get('/product-images/{id}',[ProductController::class,'images'])->name('product.images');
    Route::get('/notification/{id}',[NotificationControlle::class,'show'])->name('admin.notification');
    Route::get('/notifications',[NotificationController::class, 'index'])->name('all.notification');
    Route::delete('/notification/{id}',[NotificationController::class, 'delete'])->name('notification.delete');

    Route::get('/profile',[HomeController::class, 'profile'])->name('admin-profile');
    Route::post('/profile/{id}',[HomeController::class,'profileUpdate'])->name('profile-update');

    Route::get('change-password', [HomeController::class,'changePassword'])->name('change.password.form');
    Route::post('change-password', [HomeController::class,'changPasswordStore'])->name('change.password');
});
