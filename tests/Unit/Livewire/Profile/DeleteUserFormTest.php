<?php

declare(strict_types=1);

use App\Livewire\Profile\DeleteUserForm;
use App\Models\User;
use Livewire\Livewire;

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'password')
        ->call('deleteUser');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $component
        ->assertHasErrors('password')
        ->assertNoRedirect();

    $this->assertNotNull($user->fresh());
});

test('unauthenticated users are redirected to login', function () {
    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'any-password');

    $component->call('deleteUser');

    $component->assertRedirect(route('login'));
});
