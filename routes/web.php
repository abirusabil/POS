<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OutletsController;
use App\Http\Controllers\MyBusinessController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('businesses', BusinessController::class);
    Route::resource('outlets', OutletsController::class)->only(['store', 'update', 'destroy']);
    Route::get('/business',  [MyBusinessController::class, 'edit'])->name('business.edit');   // tampilkan halaman
    Route::post('/business', [MyBusinessController::class, 'store'])->name('business.store');  // Card 1: create
    Route::put('/business',  [MyBusinessController::class, 'update'])->name('business.update');// Card 1: update
});

require __DIR__.'/settings.php';
