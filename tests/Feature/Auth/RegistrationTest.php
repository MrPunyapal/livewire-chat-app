<?php

declare(strict_types=1);

use App\Livewire\Pages\Auth\Register;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeLivewire(Register::class);
});
