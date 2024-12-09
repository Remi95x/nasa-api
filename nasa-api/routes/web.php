<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NasaController;

Route::get('/', [NasaController::class, 'index']);
Route::post('/fetch-data', [NasaController::class, 'fetchData'])->name('fetch.data');  
Route::get('/fetch-data', [NasaController::class, 'fetchData'])->name('fetch.data');  
