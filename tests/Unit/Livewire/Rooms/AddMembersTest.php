<?php

declare(strict_types=1);

use App\Livewire\Rooms\AddMembers;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

it('can render add member component with non-member users', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory()->create(), relationship: 'users')
        ->create();

    $nonMember = User::factory()->create(['name' => 'Non Member User']);

    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->assertStatus(200)
        ->assertSee('Add member')
        ->assertSee('Non Member User')
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

    $newMember = User::factory()->create();
    Livewire::test(AddMembers::class, ['room' => $room, 'existingMembers' => $room->users])
        ->set('members', [$newMember->id])
        ->call('submit')
        ->assertHasNoErrors();
});
