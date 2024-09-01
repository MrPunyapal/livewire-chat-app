<?php

declare(strict_types=1);

use App\Livewire\Pages\Auth\ConfirmPassword;
use App\Models\User;
use Livewire\Livewire;

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(ConfirmPassword::class)
        ->set('password', 'password');

    $component->call('confirmPassword');

    $component
        ->assertRedirect('/dashboard')
        ->assertHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(ConfirmPassword::class)
        ->set('password', 'wrong-password');

    $component->call('confirmPassword');

    $component
        ->assertNoRedirect()
        ->assertHasErrors('password');
});
