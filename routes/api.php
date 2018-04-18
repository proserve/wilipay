<?php
Route::prefix('v1')->group(function () {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::put('/users/phone', 'RegisterController@editPhone')->middleware('auth:api');
    Route::put('/users/profile', 'ProfileController@editProfile')->middleware('auth:api');
    Route::put('/users/profile/address', 'ProfileController@editAddress')->middleware('auth:api');
    Route::post('/users/profile/avatar', 'ProfileController@editUserAvatar')->middleware('auth:api');
    Route::get('/users/accounts', 'AccountController@list')->middleware('auth:api');

    // Transactions routes
    Route::get('/transactions', 'TransactionController@list')->middleware('auth:api');
    Route::post('/transactions/between_users', 'TransactionController@betweenUsers')->middleware('auth:api');
    Route::post('/transactions/convert', 'TransactionController@convert')->middleware('auth:api');
    Route::post('/transactions/top-up/card', 'TransactionController@topUp')->middleware('auth:api');
    Route::get('/currencies/rates', 'TransactionController@getCurrenciesRates')->middleware('auth:api');

    // CARDS
    Route::post('/cards', 'CardController@add')->middleware('auth:api');
    Route::get('/cards', 'CardController@list')->middleware('auth:api');
});

