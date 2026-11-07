<?php

use App\Http\Controllers\TesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TesController::class, 'beranda'])->name('beranda');
Route::get('/tes', [TesController::class, 'tes'])->name('tes');
Route::post('/tes', [TesController::class, 'simpan'])->middleware('throttle:tes-submissions')->name('tes.simpan');
Route::get('/hasil/{submission:uuid}', [TesController::class, 'hasil'])->name('hasil');
