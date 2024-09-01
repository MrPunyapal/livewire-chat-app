<?php

declare(strict_types=1);

use App\Livewire\Pages\Auth\Register;
use Livewire\Livewire;

test('new users can register', function () {
    $component = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});
