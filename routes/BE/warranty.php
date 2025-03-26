<?php

Route::get('warranty', 'WarrantyController@index');
Route::get('warranty/getList', 'WarrantyController@list');
Route::get('warranty/{id}', 'WarrantyController@show');
Route::post('warranty', 'WarrantyController@store');
Route::put('warranty/{id}', 'WarrantyController@update');
Route::delete('warranty', 'WarrantyController@destroy');



