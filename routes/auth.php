<?php

declare(strict_types=1);

use App\Livewire\Actions\Logout;
use App\Livewire\Pages\Auth\ConfirmPassword;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ResetPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::livewire('register', Register::class)
        ->name('register');

    Route::livewire('login', Login::class)
        ->name('login');

    Route::livewire('forgot-password', ForgotPassword::class)
        ->name('password.request');

    Route::livewire('reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});

Route::middleware('auth')->group(function (): void {
    Route::livewire('confirm-password', ConfirmPassword::class)
        ->name('password.confirm');

    Route::post('logout', function (Logout $logout): void {
        $logout();
        redirect('/')->send();
    })->name('logout');
});
