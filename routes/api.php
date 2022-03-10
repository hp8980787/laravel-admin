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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::post('user/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    Route::post('user/register', [\App\Http\Controllers\Api\UserController::class, 'register']);
    Route::get('user/info',[\App\Http\Controllers\Api\UserController::class,'info']);
    Route::delete('user/logout',[\App\Http\Controllers\Api\UserController::class,'logout']);
    Route::get('orders',[\App\Http\Controllers\Api\OrderController::class,'index']);
});
