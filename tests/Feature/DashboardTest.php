<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected to the login page', function (): void {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});
test('authenticated users can visit the dashboard', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee(route('chats'));
});
test('authenticated users can visit the chats route', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('chats'));

    $response->assertOk();
});
test('authenticated users are redirected to profile settings from profile route', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('profile'));

    $response->assertRedirect(route('profile.edit'));
});
