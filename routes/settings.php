<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::livewire('settings/security', 'pages::settings.security')->name('security.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('appearance.edit');
});
