<?php

use Illuminate\Support\Facades\Route;

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
/**
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Auth\LoginController@show']);
Route::post('authenticate', ['as' => 'authenticate', 'uses' => 'App\Http\Controllers\Auth\LoginController@store']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\Auth\LoginController@logout']);

Route::get('register', ['as' => 'register', 'uses' => 'App\Http\Controllers\Auth\RegisterController@show']);
Route::post('register', ['as' => 'create_user', 'uses' => 'App\Http\Controllers\Auth\RegisterController@store']);


Route::get('/', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\HomeController@dashboard']);

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => ['auth']], function () {
    Route::get('buy/product/{id}', ['as' => 'buy_product', 'uses' => 'ProductController@buy']);
    Route::resource('orders', 'OrderController', ['only' => ['index']])->middleware('role:Admin');
    Route::get('customer/order', ['as' => 'customer_order', 'uses' => 'CustomerOrderController@index']);
    Route::get('pay_order', ['as' => 'pay_order', 'uses' => 'PaymentController@show']);
    Route::get('process_pay', ['as' => 'process_pay', 'uses' => 'PaymentController@process']);
    Route::get('update/order/state', ['as' => 'update_order_state', 'uses' => 'PaymentController@updateOrderState']);
});
