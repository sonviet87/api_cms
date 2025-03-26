<?php

Route::get('salaries', 'SalaryController@index');
Route::get('salaries/getList', 'SalaryController@list');
Route::get('salaries/{id}', 'SalaryController@show');
Route::post('salaries', 'SalaryController@store');
Route::put('salaries/{id}', 'SalaryController@update');
Route::delete('salaries', 'SalaryController@destroy');



