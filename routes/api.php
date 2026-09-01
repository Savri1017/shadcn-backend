<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\MediaController;

Route::apiResource('penggunas', PenggunaController::class);
Route::apiResource('jabatans', JabatanController::class)->only(['index', 'store']);

Route::get('media/pengguna/{pengguna}', [MediaController::class, 'indexForPengguna']);
Route::post('media/pengguna/{pengguna}', [MediaController::class, 'storeForPengguna']);
Route::delete('media/{media}', [MediaController::class, 'destroy']);
