<?php

Route::get('costs-fixed', 'CostsFixedController@index');
Route::get('costs-fixed/getList', 'CostsFixedController@list');
Route::get('costs-fixed/{id}', 'CostsFixedController@show');
Route::post('costs-fixed', 'CostsFixedController@store');
Route::put('costs-fixed/{id}', 'CostsFixedController@update');
Route::delete('costs-fixed', 'CostsFixedController@destroy');



