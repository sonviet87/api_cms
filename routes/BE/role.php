<?php

Route::get('roles', 'RoleController@index');
Route::get('roles/{id}', 'RoleController@show');
Route::post('roles', 'RoleController@store');
Route::put('roles/{id}', 'RoleController@update');
Route::delete('roles', 'RoleController@destroy');


