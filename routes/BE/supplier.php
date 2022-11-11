<?php

Route::get('suppliers', 'SupplierController@index');
Route::get('suppliers/{id}', 'SupplierController@show');
Route::post('suppliers', 'SupplierController@store');
Route::put('suppliers/{id}', 'SupplierController@update');
Route::delete('suppliers', 'SupplierController@destroy');


