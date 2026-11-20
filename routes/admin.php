<?php

use App\Http\Controllers\Admin\SoftwareController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/saran', [SuggestionController::class, 'create'])->name('suggestions.create');
Route::post('/saran', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/software')->name('home');
    Route::resource('software', SoftwareController::class)->except('show');
});
