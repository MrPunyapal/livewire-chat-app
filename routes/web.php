<?php

declare(strict_types=1);

use App\Livewire\Pages\Chats;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Profile;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')
    ->group(function (): void {
        Route::livewire('dashboard', Dashboard::class)
            ->name('dashboard');

        Route::livewire('profile', Profile::class)
            ->name('profile');

        Route::livewire('chats', Chats::class)
            ->name('chats');
    });

require __DIR__.'/auth.php';
