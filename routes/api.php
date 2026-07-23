<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use APP\Http\Controllers\ConversionController;
use APP\Htpp\Controllers\DownloadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/convert', [App\Http\Controllers\ConversionController::class, 'store']);
Route::get('/download/{id}', [App\Http\Controllers\DownloadController::class, 'show']);
Route::get('/status/{id}', [App\Http\Controllers\ConversionController::class, 'status']);
