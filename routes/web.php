<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::view('dashboard', 'patxiai-patxi::pages.dashboard.dashboard.index')->name('dashboard');
    Route::view('chat', 'patxiai-patxi::pages.dashboard.chat.index')->name('chat');
    Route::livewire('agents', 'patxiai-patxi::pages.dashboard.agents.index')->name('agents');
    Route::view('applications', 'patxiai-patxi::pages.dashboard.applications.index')->name('applications');
    Route::livewire('integrations', 'patxiai-patxi::pages.dashboard.integrations.index')->name('integrations');
});

require __DIR__.'/settings.php';
