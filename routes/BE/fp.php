<?php

Route::get('fps', 'FPController@index');
Route::get('fps/{id}', 'FPController@show');
Route::post('fps', 'FPController@store');
Route::put('fps/{id}', 'FPController@update');
Route::delete('fps', 'FPController@destroy');


