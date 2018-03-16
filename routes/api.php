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

Route::post('/ak-login', 'RegisterController@otpLogin');
Route::post('/register', 'RegisterController@register');
Route::put('/user/phone', 'RegisterController@editPhone')->middleware('auth:api');
Route::put('/user/profile', 'ProfileController@editProfile')->middleware('auth:api');
Route::put('/user/profile/address', 'ProfileController@editAddress')->middleware('auth:api');
Route::post('/user/profile/avatar', 'ProfileController@editUserAvatar')->middleware('auth:api');
Route::get('/test-passport', function (Request $request){
    return response('right');
})->middleware('auth:api');