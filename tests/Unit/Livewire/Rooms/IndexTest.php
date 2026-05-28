<?php

declare(strict_types=1);

use App\Livewire\Rooms\Index;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Livewire;

test('sidebar component limits rooms and keeps newest first', function (): void {
    $user = User::factory()
        ->create();

    foreach (range(1, Index::LIMIT + 2) as $index) {
        Room::factory()
            ->hasAttached($user, relationship: 'users')
            ->create([
                'name' => 'Room '.$index,
            ]);
    }

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('rooms', fn (Collection $rooms): bool => $rooms->count() === Index::LIMIT)
        ->assertDontSee('No rooms found');
});

test('sidebar component loads more rooms and stops when exhausted', function (): void {
    $user = User::factory()->create();

    foreach (range(1, Index::LIMIT + 2) as $index) {
        Room::factory()
            ->hasAttached($user, relationship: 'users')
            ->create([
                'name' => 'Room '.$index,
            ]);
    }

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('loadMore')
        ->assertSet('offset', Index::LIMIT)
        ->assertViewHas('rooms', fn (Collection $rooms): bool => $rooms->count() === 2)
        ->assertDispatched('no-more-rooms');
});

test('sidebar component without rooms', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('No rooms found');
});

test('sidebar component can show active room', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->dispatch('room-selected', id: $room->id)
        ->assertSet('activeRoomId', $room->id);
});

test('search rooms', function (): void {
    $user = User::factory()
        ->create();

    foreach (range(1, Index::LIMIT + 2) as $index) {
        Room::factory()
            ->hasAttached($user, relationship: 'users')
            ->create([
                'name' => 'Room '.$index,
            ]);
    }

    $component = Livewire::actingAs($user)
        ->test(Index::class)
        ->set('offset', Index::LIMIT)
        ->set('search', 'Room 12');

    $component->assertSet('offset', 0)
        ->assertViewHas('rooms', fn (Collection $rooms): bool => $rooms->count() === 1)
        ->assertHasNoErrors('search')
        ->assertOk();
});

test('search with no match shows empty state', function (): void {
    $user = User::factory()->create();

    $rooms = Room::factory(3)
        ->hasAttached($user, relationship: 'users')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['search' => 'room-that-does-not-exist'])
        ->assertViewHas('rooms', fn ($rooms) => $rooms->isEmpty())
        ->assertSee('No rooms found');
});
