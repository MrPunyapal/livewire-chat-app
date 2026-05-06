<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('security settings page can be rendered', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('security.edit'))
        ->assertOk();
});

test('password can be updated', function (): void {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $this->actingAs($user);

    Livewire::test('pages::settings.security')
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function (): void {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $this->actingAs($user);

    Livewire::test('pages::settings.security')
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);
});
