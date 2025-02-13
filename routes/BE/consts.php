<?php

Route::get('costs', 'CostsController@index');
Route::get('costs/getList', 'CostsController@list');
Route::get('costs/{id}', 'CostsController@show');
Route::post('costs', 'CostsController@store');
Route::put('costs/{id}', 'CostsController@update');
Route::delete('costs', 'CostsController@destroy');



