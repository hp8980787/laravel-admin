<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('user')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\Api\UserController::class, 'register']);
        Route::get('info', [\App\Http\Controllers\Api\UserController::class, 'info']);
        Route::delete('logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);

    });
    Route::middleware(['auth:sanctum', 'admin.permission'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::resource('roles', \App\Http\Controllers\Api\RoleController::class)->parameters(['roles' => 'id']);
            Route::resource('permissions', \App\Http\Controllers\Api\PermissionController::class)->parameter('permissions', 'id');
            Route::post('assign-roles', [\App\Http\Controllers\Api\UserController::class, 'assignRole']);
        });
        Route::post('roles/assign-permissions', [\App\Http\Controllers\Api\RoleController::class, 'assignPermissions']);
        Route::get('orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
        Route::get('orders/todaySales', [\App\Http\Controllers\Api\OrderController::class, 'todaySales']);
    });

});
