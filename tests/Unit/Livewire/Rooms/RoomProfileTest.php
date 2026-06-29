<?php

declare(strict_types=1);

use App\Livewire\Rooms\RoomProfile;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

it('room profile renders room detail with members', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory()->create(), relationship: 'users')
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create(['name' => 'Test Room']);

    Livewire::test(RoomProfile::class, ['roomId' => $room->id])
        ->assertStatus(200)
        ->assertSee('Room info')
        ->assertSee('Test Room')
        ->assertSee($room->users->count().' members')
        ->assertSee($room->user->name)
        ->assertViewIs('livewire.rooms.room-profile');
});
