<?php
Route::get('kpi-settings-1', 'KpiSettingsController@index');
Route::get('kpi-settings-1/{id}', 'KpiSettingsController@show');
Route::post('kpi-settings-1', 'KpiSettingsController@store');
Route::put('kpi-settings-1/{id}', 'KpiSettingsController@update');
Route::delete('kpi-settings-1', 'KpiSettingsController@destroy');


