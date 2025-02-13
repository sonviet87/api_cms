<?php

Route::get('costs-team', 'CostsTeamController@index');
Route::get('costs-team/getList', 'CostsTeamController@list');
Route::get('costs-team/{id}', 'CostsTeamController@show');
Route::post('costs-team', 'CostsTeamController@store');
Route::put('costs-team/{id}', 'CostsTeamController@update');
Route::delete('costs-team', 'CostsTeamController@destroy');



