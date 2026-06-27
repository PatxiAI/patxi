<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::view('dashboard', 'patxiai-patxi::pages.dashboard.dashboard.index')->name('dashboard');
});

// require __DIR__.'/settings.php';
