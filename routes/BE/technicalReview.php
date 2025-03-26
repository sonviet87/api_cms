<?php

Route::get('technical-review', 'TechnicalReviewController@index');
Route::get('technical-review/getList', 'TechnicalReviewController@list');
Route::get('technical-review/{id}', 'TechnicalReviewController@show');
Route::post('technical-review', 'TechnicalReviewController@store');
Route::put('technical-review/{id}', 'TechnicalReviewController@update');
Route::delete('technical-review', 'TechnicalReviewController@destroy');


