<?php

declare(strict_types=1);

use App\Livewire\Pages\Chats;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('profile', 'settings/profile')->name('profile');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('chats', Chats::class)->name('chats');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
