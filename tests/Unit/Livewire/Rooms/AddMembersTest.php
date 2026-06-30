<?php

declare(strict_types=1);

use App\Livewire\Rooms\AddMembers;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

it('can render add member component', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory()->create(), relationship: 'users')
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create();

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->assertStatus(200)
        ->assertSee('Add member')
        ->assertSee($room->users->first()->name)
        ->assertSee($room->users->last()->name)
        ->assertViewIs('livewire.rooms.add-members');
});

it('validates the members field', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create();

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', [])
        ->call('submit')
        ->assertHasErrors(['members']);

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', [999])
        ->call('submit')
        ->assertHasErrors(['members.*']);

    $existingMemberIds = array_merge($room->users->pluck('id')->toArray(), [$room->user->id]);
    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', $existingMemberIds)
        ->call('submit')
        ->assertHasNoErrors();
});
