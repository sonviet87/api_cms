<?php

Route::get('technical-project', 'TechnicalProjectController@index');
Route::get('technical-project/getList', 'TechnicalProjectController@list');
Route::get('technical-project/{id}', 'TechnicalProjectController@show');
Route::post('technical-project', 'TechnicalProjectController@store');
Route::put('technical-project/{id}', 'TechnicalProjectController@update');
Route::delete('technical-project', 'TechnicalProjectController@destroy');


