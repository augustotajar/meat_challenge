<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Product
Route::apiResource('products', 'ProductController');

//Cart
Route::middleware('auth:api')->resource('carts', 'CartController')->only(['index', 'destroy']);
Route::middleware('auth:api')->put('/carts/addProduct', 'CartController@addProduct');
Route::middleware('auth:api')->put('/carts/removeProduct', 'CartController@removeProduct');

//Payments
Route::apiResource('payments', 'PaymentController')->except(['update','destroy']);