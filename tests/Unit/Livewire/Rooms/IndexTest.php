<?php

declare(strict_types=1);

use App\Livewire\Rooms\Index;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('sidebar component contains rooms', function (): void {
    $user = User::factory()
        ->create();

    $rooms = Room::factory(5)
        ->hasAttached($user, relationship: 'users')
        ->create();

    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('rooms', $rooms)
        ->assertDontSee($room->name)
        ->assertDontSee('No rooms found');
});

test('sidebar component without rooms', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('No rooms found');
});

test('sidebar component can show active room', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->dispatch('room-selected', id: $room->id)
        ->assertSet('activeRoomId', $room->id);
});
