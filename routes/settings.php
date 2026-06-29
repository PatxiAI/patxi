<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    // Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'patxiai-patxi::pages.dashboard.settings.profile')->name('profile.edit');
});

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', 'patxiai-patxi::pages.dashboard.settings.appearance')->name('appearance.edit');

    Route::livewire('settings/security', 'patxiai-patxi::pages.dashboard.settings.security')
        /* @chisel-password-confirmation */
        ->middleware([
            'password.confirm',
        ])
        /* @end-chisel-password-confirmation */
        ->name('security.edit');
});
