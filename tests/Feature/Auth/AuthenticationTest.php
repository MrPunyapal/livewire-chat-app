<?php

declare(strict_types=1);

use App\Livewire\Pages\Auth\Login;
use App\Models\User;

test('login screen can be rendered', function (): void {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeLivewire(Login::class);
});
