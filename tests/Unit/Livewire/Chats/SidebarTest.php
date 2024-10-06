<?php

declare(strict_types=1);
use App\Livewire\Chats\Sidebar;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('sidebar component contains rooms', function () {
    $user = User::factory()
        ->create();

    $rooms = Room::factory(5)
        ->hasAttached($user, relationship: 'users')
        ->create();

    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Sidebar::class)
        ->assertViewHas('rooms', $rooms)
        ->assertDontSee($room->name)
        ->assertDontSee('No rooms found');
});

test('sidebar component without rooms', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Sidebar::class)
        ->assertSee('No rooms found');
});
