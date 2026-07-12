<?php

declare(strict_types=1);

use App\Livewire\Rooms\RoomProfile;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('room profile renders room detail with members', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create(['name' => 'Test Room']);

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->assertStatus(200)
        ->assertSee('Room info')
        ->assertSee('Test Room')
        ->assertSee($room->users->count().' members')
        ->assertSee($room->user->name)
        ->assertViewIs('livewire.rooms.room-profile');
});

test('room profile denies access for unauthorized users', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory()->create(), relationship: 'users')
        ->create();

    $otherUser = User::factory()->create();

    Livewire::actingAs($otherUser)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->assertStatus(403);
});
