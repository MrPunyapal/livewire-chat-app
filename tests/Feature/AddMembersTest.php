<?php

declare(strict_types=1);

use App\Livewire\Rooms\AddMembers;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('user can add new members to the room', function (): void {

    $user = User::factory()->create();

    $room = Room::factory()
        ->for($user, relationship: 'user')
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create();

    expect($room->users->count())->toBe(3);

    $newMembers = User::factory(2)->create();

    $existingMemberIds = array_merge($room->users->pluck('id')->toArray(), [$room->user->id]);

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', [
            ...$existingMemberIds,
            ...$newMembers->pluck('id')->all(),
        ])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatched('members-added');

    expect($room->users()->count())->toBe(6);

    $this->assertDatabaseHas('members', ['user_id' => $newMembers->first()->id, 'room_id' => $room->id]);
    $this->assertDatabaseHas('members', ['user_id' => $newMembers->last()->id, 'room_id' => $room->id]);
});
