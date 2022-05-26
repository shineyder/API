<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResourceController;
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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [UserController::class, 'create'])->name('auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::middleware('apiJWT')->get('/user', [AuthController::class, 'userProfile'])->name('auth.userProfile');
});

Route::middleware('apiJWT')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/{id}', [UserController::class, 'info'])->name('user.info');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/permission', [UserPermissionController::class, 'updateMultiplePermission'])->name('permission.update');
        /* Route::middleware('apiJWT')->post('/permission', [UserPermissionController::class, 'updateOnePermission'])->name('permission.update'); */
    });

    Route::get('/resource', [ResourceController::class, 'list'])->name('permission.list');
});
