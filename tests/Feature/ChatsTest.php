<?php

declare(strict_types=1);

use App\Livewire\Chats\Index as ChatsIndex;
use App\Livewire\Chats\ListChats;
use App\Livewire\Chats\Save as CreateChat;
use App\Livewire\Pages\Chats;
use App\Livewire\Rooms\Create as CreateRoom;
use App\Livewire\Rooms\Index as RoomsIndex;
use App\Models\Room;
use App\Models\User;

test('chats page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/chats')
        ->assertSeeLivewire(Chats::class)
        ->assertSeeLivewire(ChatsIndex::class)
        ->assertSeeLivewire(RoomsIndex::class)
        ->assertSeeLivewire(CreateRoom::class)
        ->assertDontSeeLivewire(CreateChat::class)
        ->assertOk();
});

test('create chat component should be there if room is selected', function (): void {
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
        ->assertSeeLivewire(ListChats::class)
        ->assertOk();
});
