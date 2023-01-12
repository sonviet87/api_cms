<?php

Route::get('categories', 'CategoryController@index');
Route::get('categories/getList', 'CategoryController@list');
Route::get('categories/{id}', 'CategoryController@show');
Route::post('categories', 'CategoryController@store');
Route::put('categories/{id}', 'CategoryController@update');
Route::delete('categories', 'CategoryController@destroy');
Route::post('categories/import', 'CategoryController@import');


