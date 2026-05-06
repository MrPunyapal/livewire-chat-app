<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected to the login page', function (): void {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk();
});

test('authenticated users can visit the chats route', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('chats'))
        ->assertOk();
});

test('authenticated users are redirected to profile settings from profile route', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('profile'))
        ->assertRedirect(route('profile.edit'));
});
