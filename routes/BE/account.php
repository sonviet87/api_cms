<?php

Route::get('accounts', 'AccountController@index');
Route::get('accounts/{id}', 'AccountController@show');
Route::post('accounts', 'AccountController@store');
Route::put('accounts/{id}', 'AccountController@update');
Route::delete('accounts', 'AccountController@destroy');
Route::get('accounts/contacts/{id}', 'AccountController@getListContactByID');


