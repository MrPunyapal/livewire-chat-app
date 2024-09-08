<?php

declare(strict_types=1);

use App\Livewire\Chats\Index;
use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

it('renders with room and chats', function () {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $chats = Chat::factory()->count(10)->create(['room_id' => $room->id]);

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertViewHas('room', $room)
        ->assertDontSee('Please select room.')
        ->assertDontSee('No chats found')
        ->assertViewHas('chats', $chats);
});

it('renders without room and chats', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('room', null)
        ->assertSee('Please select room.')
        ->assertSee('No chats found')
        ->assertViewHas('chats', []);
});
