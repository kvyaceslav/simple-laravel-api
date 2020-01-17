<?php

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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('details', 'API\UserController@details');

    // Categories
    Route::get('category', 'API\CategoryController@index');
    Route::get('category/{category}', 'API\CategoryController@show');
    Route::post('category', 'API\CategoryController@create');
    Route::patch('category/{category}', 'API\CategoryController@update');
    Route::delete('category/{category}', 'API\CategoryController@delete');

    // Products
    Route::get('product', 'API\ProductController@index');
    Route::get('product/{product}', 'API\ProductController@show');
    Route::post('product', 'API\ProductController@create');
    Route::patch('product/{product}', 'API\ProductController@update');
    Route::delete('product/{product}', 'API\ProductController@delete');
    Route::patch('product/{product}/category/{category}', 'API\ProductController@removeCategory');
});
