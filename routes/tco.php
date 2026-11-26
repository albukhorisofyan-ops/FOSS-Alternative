<?php

use App\Http\Controllers\TcoController;
use Illuminate\Support\Facades\Route;

Route::get('/kalkulator', [TcoController::class, 'index'])->name('tco.index');
Route::post('/kalkulator', [TcoController::class, 'hitung'])->name('tco.hitung');
