<?php

Route::get('contracts', 'ContractController@index');
Route::get('contracts/getList', 'ContractController@list');
Route::get('contracts/{id}', 'ContractController@show');
Route::post('contracts', 'ContractController@store');
Route::put('contracts/{id}', 'ContractController@update');
Route::delete('contracts', 'ContractController@destroy');


