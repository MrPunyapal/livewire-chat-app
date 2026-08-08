<?php

declare(strict_types=1);

use App\Livewire\Rooms\AddMembers;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('user can add new members to the room', function (): void {
    $user = User::factory()->create();

    $room = Room::factory()
        ->for($user, relationship: 'user')
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create();

    expect($room->users->count())->toBe(3);

    $newMembers = User::factory(2)->create();

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', $newMembers->pluck('id')->all())
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatched('members-added');

    expect($room->users()->count())->toBe(5);

    $this->assertDatabaseHas('members', ['user_id' => $newMembers->first()->id, 'room_id' => $room->id]);
    $this->assertDatabaseHas('members', ['user_id' => $newMembers->last()->id, 'room_id' => $room->id]);
});

test('user can add single member to the room', function (): void {
    $user = User::factory()->create();

    $room = Room::factory()
        ->for($user, relationship: 'user')
        ->hasAttached(User::factory(2)->create(), relationship: 'users')
        ->create();

    $newMember = User::factory()->create();

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', [$newMember->id])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatched('members-added');

    expect($room->users()->count())->toBe(3);
    $this->assertDatabaseHas('members', ['user_id' => $newMember->id, 'room_id' => $room->id]);
});
