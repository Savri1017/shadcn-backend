<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\JabatanController;

Route::apiResource('penggunas', PenggunaController::class);
Route::apiResource('jabatans', JabatanController::class)->only(['index', 'store']);