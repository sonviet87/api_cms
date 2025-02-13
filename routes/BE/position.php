<?php

Route::get('positions', 'PositionController@index');
Route::get('positions/getList', 'PositionController@list');
Route::get('positions/{id}', 'PositionController@show');
Route::post('positions', 'PositionController@store');
Route::put('positions/{id}', 'PositionController@update');
Route::delete('positions', 'PositionController@destroy');



