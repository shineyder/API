<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPermissionController;

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

/*
Route::get('auth/login', [AuthController::class, 'index'])->name('login');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth/login');

Route::get('register', [UserController::class, 'index'])->name('register.index');
Route::post('register', [UserController::class, 'register'])->name('register.register');

Route::get('back', [AuthController::class, 'error'])->name('back');

Route::middleware('apiJWT')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::post('delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('update', [UserController::class, 'update'])->name('user.update');

    Route::get('brand', [BrandController::class, 'index'])->name('brand.index');
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('product', [ProductController::class, 'index'])->name('product.index');

    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');
});
*/


Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/register', [UserController::class, 'create'])->name('auth.register');
Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');


//Route::post('user', [UserController::class, 'create'])->name('user.create');

Route::prefix('user')->middleware('apiJWT')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{id}', [UserController::class, 'info'])->name('user.info');
    Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
});

Route::middleware('apiJWT')->post('/permission', [UserPermissionController::class, 'updateUserPermission'])->name('permission.update');
