<?php

Route::get('debts', 'DebtController@index');
Route::get('debts/{id}', 'DebtController@show');
Route::post('debts', 'DebtController@store');
Route::put('debts/{id}', 'DebtController@update');
Route::delete('debts', 'DebtController@destroy');



