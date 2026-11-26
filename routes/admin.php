<?php

use App\Http\Controllers\Admin\SoftwareController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/saran', [SuggestionController::class, 'create'])->name('suggestions.create');
Route::post('/saran', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.software.index'))->name('home');
    Route::resource('software', SoftwareController::class)->except(['show']);
    Route::get('/suggestions', [SuggestionController::class, 'adminIndex'])->name('suggestions.index');
    Route::patch('/suggestions/{suggestion}', [SuggestionController::class, 'updateStatus'])->name('suggestions.update');
});
