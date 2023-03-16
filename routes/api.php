<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'index']);

Route::group(['middleware' => 'api.auth'], function () {
    Route::get('user', [LoginController::class, 'details']);
    Route::get('logout', [LoginController::class, 'logout']);

    Route::apiResource('product', ProductController::class);
    Route::apiResource('category', CategoryController::class);
});
