<?php
Route::get('kpi-settings', 'KpiSettingsController@index');
Route::get('kpi-settings/{id}', 'KpiSettingsController@show');
Route::post('kpi-settings', 'KpiSettingsController@store');
Route::put('kpi-settings/{id}', 'KpiSettingsController@update');
Route::delete('kpi-settings', 'KpiSettingsController@destroy');


