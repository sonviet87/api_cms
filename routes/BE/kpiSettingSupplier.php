<?php

Route::get('kpi-setting-supplier', 'KpiSettingSupplierController@index');
Route::get('kpi-setting-supplier/getList', 'KpiSettingSupplierController@list');
Route::get('kpi-setting-supplier/{id}', 'KpiSettingSupplierController@show');
Route::post('kpi-setting-supplier', 'KpiSettingSupplierController@store');
Route::put('kpi-setting-supplier/{id}', 'KpiSettingSupplierController@update');
Route::delete('kpi-setting-supplier', 'KpiSettingSupplierController@destroy');
