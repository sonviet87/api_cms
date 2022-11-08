<?php

Route::get('contacts', 'ContactController@index');
Route::get('contacts/{id}', 'ContactController@show');
Route::post('contacts', 'ContactController@store');
Route::put('contacts/{id}', 'ContactController@update');
Route::delete('contacts', 'ContactController@destroy');


