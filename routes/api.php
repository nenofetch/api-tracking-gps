<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

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

Route::post('register', [API\RegisterController::class, 'register']);
Route::post('login', [API\LoginController::class, 'login']);
// Route::post('logout', [API\LoginController::class, 'logout']);


Route::middleware('auth:api')->group(function () {
    Route::middleware('role:user')->group(function () {
        Route::get('user/{id}', [API\UserController::class, 'show']);
        Route::put('user/{id}', [API\UserController::class, 'update']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('user', API\UserController::class);
    });
});

Route::apiResource('history', API\HistoryController::class);
