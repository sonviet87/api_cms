<?php

Route::get('technical-certificate', 'TechnicalCertificateController@index');
Route::get('technical-certificate/getList', 'TechnicalCertificateController@list');
Route::get('technical-certificate/{id}', 'TechnicalCertificateController@show');
Route::post('technical-certificate', 'TechnicalCertificateController@store');
Route::put('technical-certificate/{id}', 'TechnicalCertificateController@update');
Route::delete('technical-certificate', 'TechnicalCertificateController@destroy');


