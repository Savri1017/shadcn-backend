<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\MediaController;

Route::apiResource('penggunas', PenggunaController::class);
Route::apiResource('jabatans', JabatanController::class)->only(['index', 'store']);

Route::get('media/{type}/{id}', [MediaController::class, 'index']);
Route::post('media/{type}/{id}', [MediaController::class, 'store']);
Route::delete('media/{media}', [MediaController::class, 'destroy']);
