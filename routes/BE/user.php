<?php

Route::get('users', 'UserController@index');
Route::get('users/getList', 'UserController@list');
Route::get('users/{id}', 'UserController@show');
Route::post('users', 'UserController@store');
Route::put('users/{id}', 'UserController@update');
Route::delete('users', 'UserController@destroy');
Route::post('users/check-password', 'UserController@checkPassword');
Route::post('users/change-password', 'UserController@changePassword');
Route::get('user', 'UserController@getUser');



