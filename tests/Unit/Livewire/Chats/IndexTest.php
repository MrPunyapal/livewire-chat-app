<?php

declare(strict_types=1);

use App\Livewire\Chats\Index;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

it('renders with room', function () {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertViewHas('room', $room)
        ->assertDontSee('Please select room.');
});

it('renders without room', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('room', null)
        ->assertSee('Select a room to start chatting');
});

it('selects room', function () {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->dispatch('room-selected', id: $room->id)
        ->assertSet('roomId', $room->id);
});

it('renders room only if user is a member', function () {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $room->users()->attach($user);

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertViewHas('room', $room)
        ->assertDontSee('Please select room.');
});
