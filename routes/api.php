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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('v1')->group(function () {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::put('/users/phone', 'RegisterController@editPhone')->middleware('auth:api');
    Route::put('/users/profile', 'ProfileController@editProfile')->middleware('auth:api');
    Route::put('/users/profile/address', 'ProfileController@editAddress')->middleware('auth:api');
    Route::post('/users/profile/avatar', 'ProfileController@editUserAvatar')->middleware('auth:api');
    Route::get('/users/accounts', 'AccountController@list')->middleware('auth:api');

    // Those api are for admin part
    Route::get('/users', 'UserController@list');
    Route::get('/users/{id}', 'UserController@show');
    Route::post('/users', 'UserController@create');
    Route::delete('/users/{id}', 'UserController@destroy');

    // Transactions routes
    Route::post('/transactions/between_users', 'TransactionController@betweenUsers')->middleware('auth:api');
    Route::post('/transactions/convert', 'TransactionController@convert')->middleware('auth:api');
    Route::get('/currencies_rates', 'TransactionController@getCurrenciesRates')->middleware('auth:api');
});

