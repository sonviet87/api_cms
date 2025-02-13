<?php

Route::get('kpi-setup-user', 'KpiSetUpUserController@index');
Route::get('kpi-setup-user/getList', 'KpiSetUpUserController@list');
Route::get('kpi-setup-user/{id}', 'KpiSetUpUserController@show');
Route::get('kpi-setup-user-get-user-id/', 'KpiSetUpUserController@getIDByUser');
Route::get('kpi-setup-user-check-kpi/', 'KpiSetUpUserController@checkIdKpiCurrentYear');
Route::post('kpi-setup-user', 'KpiSetUpUserController@store');
Route::put('kpi-setup-user/{id}', 'KpiSetUpUserController@update');
Route::delete('kpi-setup-user', 'KpiSetUpUserController@destroy');
