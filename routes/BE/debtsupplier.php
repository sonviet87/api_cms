<?php

Route::get('debt-supplier', 'DebtSupplierController@index');
Route::get('debt-supplier/supplier-list-by-fp', 'DebtSupplierController@getListSupplierbyIDFP');
Route::get('debt-supplier/supplier-fpdetail', 'DebtSupplierController@getSupplierbyIDFP');
Route::get('debt-supplier/{id}', 'DebtSupplierController@show');
Route::post('debt-supplier', 'DebtSupplierController@store');
Route::put('debt-supplier/{id}', 'DebtSupplierController@update');
Route::delete('debt-supplier', 'DebtSupplierController@destroy');



