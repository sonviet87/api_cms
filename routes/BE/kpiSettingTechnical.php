<?php

Route::get('kpi-setting-technical', 'KpiSettingTechnicalController@index');
Route::get('kpi-setting-technical/getList', 'KpiSettingTechnicalController@list');
Route::get('kpi-setting-technical/{id}', 'KpiSettingTechnicalController@show');
Route::post('kpi-setting-technical', 'KpiSettingTechnicalController@store');
Route::put('kpi-setting-technical/{id}', 'KpiSettingTechnicalController@update');
Route::delete('kpi-setting-technical', 'KpiSettingTechnicalController@destroy');
