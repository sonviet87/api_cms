<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/link', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('storage:link', [] );
    echo $exitCode;
});
Route::get('/migrate', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate', [] );
    echo $exitCode;
});
Route::get('/clear-config', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('config:clear', [] );
    echo $exitCode;
});
Route::get('/clear-optimize', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('optimize:clear', [] );
    echo $exitCode;
});

