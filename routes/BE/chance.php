<?php

Route::get('chances', 'ChanceController@index');
Route::get('chances/getList', 'ChanceController@list');
Route::get('chances/{id}', 'ChanceController@show');
Route::post('chances', 'ChanceController@store');
Route::put('chances/{id}', 'ChanceController@update');
Route::delete('chances', 'ChanceController@destroy');
Route::post('chances/updateStatus', 'ChanceController@updateStatus');


