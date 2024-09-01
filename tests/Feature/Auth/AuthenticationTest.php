<?php

declare(strict_types=1);

use App\Livewire\Layout\Navigation;
use App\Livewire\Pages\Auth\Login;
use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeLivewire(Login::class);
});

test('navigation menu can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeLivewire(Navigation::class);
});
