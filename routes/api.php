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
Route::prefix('v1')->group(function () {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
    Route::put('/users/phone', 'RegisterController@editPhone')->middleware('auth:api');
    Route::put('/users/profile', 'ProfileController@editProfile')->middleware('auth:api');
    Route::put('/users/profile/address', 'ProfileController@editAddress')->middleware('auth:api');
    Route::post('/users/profile/avatar', 'ProfileController@editUserAvatar')->middleware('auth:api');
    Route::get('/users/solds', 'SoldController@list')->middleware('auth:api');

// Those api are for admin part
    Route::get('/users', 'UserController@list');
    Route::get('/users/{id}', 'UserController@show');
    Route::post('/users', 'UserController@create');
    Route::delete('/users/{id}', 'UserController@destroy');

// Currencies Routes
    Route::get('/currencies', 'CurrencyController@list');
    Route::post('/currencies', 'CurrencyController@create');
    Route::post('/currencies/{id}', 'CurrencyController@show');
    Route::put('/currencies/{id}', 'CurrencyController@edit');
    Route::delete('/currencies/{id}', 'CurrencyController@destroy');

// Transactions routes
    Route::get('/solds/{soldId}/transaction/', 'SoldController@getTransactions');
    Route::post('/solds/{soldId}/transaction/', 'SoldController@createTransaction');
    Route::post('/solds/{soldId}/transaction/{transactionID}', 'SoldController@getTransaction');
});

