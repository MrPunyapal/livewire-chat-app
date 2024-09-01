<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Livewire\Pages\Auth\ConfirmPassword;
use App\Models\User;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response
        ->assertSeeLivewire(ConfirmPassword::class)
        ->assertStatus(200);
});
