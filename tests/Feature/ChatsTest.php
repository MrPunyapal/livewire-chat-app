<?php

declare(strict_types=1);

use App\Livewire\Chats\Index as ChatsIndex;
use App\Livewire\Chats\Save as CreateChat;
use App\Livewire\Pages\Chats;
use App\Livewire\Rooms\Create as CreateRoom;
use App\Livewire\Rooms\Index as RoomsIndex;
use App\Livewire\Rooms\RoomProfile;
use App\Models\Room;
use App\Models\User;

test('chats page is displayed', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/chats')
        ->assertSeeLivewire(Chats::class)
        ->assertSeeLivewire(ChatsIndex::class)
        ->assertSeeLivewire(RoomsIndex::class)
        ->assertSeeLivewire(CreateRoom::class)
        ->assertDontSeeLivewire(CreateChat::class)
        ->assertDontSeeLivewire(RoomProfile::class)
        ->assertOk();
});

test('create chat and room profile components should be rendered if room is selected', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    $this->actingAs($user)
        ->get('/chats?roomId='.$room->id)
        ->assertSeeLivewire(Chats::class)
        ->assertSeeLivewire(ChatsIndex::class)
        ->assertSeeLivewire(RoomsIndex::class)
        ->assertSeeLivewire(CreateRoom::class)
        ->assertSeeLivewire(CreateChat::class)
        ->assertSeeLivewire(RoomProfile::class)
        ->assertOk();
});

test('room profile component does not appear when no room is selected', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/chats')
        ->assertSeeLivewire(Chats::class)
        ->assertSeeLivewire(ChatsIndex::class)
        ->assertSeeLivewire(RoomsIndex::class)
        ->assertSeeLivewire(CreateRoom::class)
        ->assertDontSeeLivewire(RoomProfile::class)
        ->assertOk();
});
