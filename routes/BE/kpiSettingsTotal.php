<?php
Route::get('kpi-settings-total', 'KpiSettingsTotalController@index');
Route::get('kpi-settings-total/{id}', 'KpiSettingsTotalController@show');
Route::post('kpi-settings-total', 'KpiSettingsTotalController@store');
Route::put('kpi-settings-total/{id}', 'KpiSettingsTotalController@update');
Route::delete('kpi-settings-total', 'KpiSettingsTotalController@destroy');


