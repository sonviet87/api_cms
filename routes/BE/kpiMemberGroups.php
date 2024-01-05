<?php

Route::get('kpi-member-groups', 'KpiMemberGroupsController@index');
Route::get('kpi-member-groups/getList', 'KpiMemberGroupsController@list');
Route::get('kpi-member-groups/{id}', 'KpiMemberGroupsController@show');
Route::post('kpi-member-groups', 'KpiMemberGroupsController@store');
Route::put('kpi-member-groups/{id}', 'KpiMemberGroupsController@update');
Route::delete('kpi-member-groups', 'KpiMemberGroupsController@destroy');


