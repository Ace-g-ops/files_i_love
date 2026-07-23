<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/convert', [App\Http\Controllers\ConversionController::class, 'store']);
Route::get('/download/{id}', [App\Http\Controllers\DownloadController::class, 'show']);
Route::get('/status/{id}', [App\Http\Controllers\ConversionController::class, 'status']);
