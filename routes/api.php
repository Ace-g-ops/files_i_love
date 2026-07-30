<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use APP\Http\Controllers\ConversionController;
use APP\Htpp\Controllers\DownloadController;
use APP\Htpp\Controllers\MediaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//CONVERSION ROUTES
Route::middleware('throttle:5,1')->group(function(){

    Route::post('/convert', [App\Http\Controllers\ConversionController::class, 'store']);
    Route::post('/convert-media', [App\Http\Controllers\MediaController::class, 'store']);

});

//STATUS ROUTES
Route::middleware('throttle:60,1')->group(function(){

    Route::get('/status/{id}', [App\Http\Controllers\ConversionController::class, 'status']); 
    Route::get('/status/media/{id}',  [App\Http\Controllers\MediaController::class, 'status']);
});

//DOWNLOAD ROUTES
Route::get('/download/{type}/{id}', [App\Http\Controllers\DownloadController::class, 'show']);

//ALPINE-FORMAT-CONFIG ROUTE
Route::get('/formats', function(){

    return response()->json(config('conversions'));
});


