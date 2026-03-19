<?php

declare(strict_types=1);

use App\Enums\ChatFilterEnum;
use App\Livewire\Chats\Index;
use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Livewire;

it('renders with room', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertViewHas('room', $room)
        ->assertDontSee('Please select room.');
});

it('renders without room', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('room')
        ->assertSee('Select a room to start chatting');
});

it('selects room', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->dispatch('room-selected', id: $room->id)
        ->assertSet('roomId', $room->id);
});

it('renders room only if user is a member', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $room->users()->attach($user);

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertViewHas('room', $room)
        ->assertDontSee('Please select room.');
});

it('filters room by favorite chats', function (): void {
    $room = Room::factory()
        ->create(['name' => 'Laragang']);

    Livewire::test(Index::class, ['roomId' => $room->id])
        ->call('toggleFilter', '')
        ->assertSet('filters', [], true)
        ->call('toggleFilter', ChatFilterEnum::Favorites->value)
        ->assertSet('filters', [ChatFilterEnum::Favorites->value], true);
});

it('filters room by favorite chats from query string', function (): void {
    $room = Room::factory()
        ->create(['name' => 'Laragang']);

    Livewire::test(Index::class, ['roomId' => $room->id, 'filters' => [ChatFilterEnum::Favorites->value]])
        ->assertSet('filters', [ChatFilterEnum::Favorites->value], true)
        ->call('toggleFilter', ChatFilterEnum::Favorites->value)
        ->assertSet('filters', [], true);
});

it('renders with room without chats', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertSet('chats', new Collection)
        ->assertSee('No messages yet');
});

it('renders with room with chats', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    $chats = Chat::factory(3)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->assertSet('chats', fn ($actual): bool => $actual->pluck('id')->sort()->values()->toArray() === $chats->pluck('id')->sort()->values()->toArray())
        ->assertDontSee('No chats found');
});

it('dispatch the chats:loaded event if offset is greater than zero', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Chat::factory(1)
        ->for($room)
        ->for($user, 'user')
        ->create();

    $chats = Chat::factory(3)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id, 'offset' => 1])
        ->assertSet('chats', fn ($actual): bool => $actual->pluck('id')->sort()->values()->toArray() === $chats->pluck('id')->sort()->values()->toArray())
        ->assertDispatched('chats:loaded');
});

it('filters room chats with favorite chats', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    $chats = Chat::factory(2)
        ->for($room)
        ->for($user, 'user')
        ->create();

    $favoritedChats = Chat::factory(3)
        ->for($room)
        ->hasAttached($user, relationship: 'favoriteUsers')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id, 'filters' => [ChatFilterEnum::Favorites->value]])
        ->assertSet('chats', fn ($actual): bool => $actual->pluck('id')->sort()->values()->toArray() === $favoritedChats->pluck('id')->sort()->values()->toArray());

    $chats = $chats->merge($favoritedChats);

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id, 'filters' => []])
        ->assertSet('chats', fn ($actual): bool => $actual->pluck('id')->sort()->values()->toArray() === $chats->pluck('id')->sort()->values()->toArray());
});

it('loads more chats and dispatches no-more-chats when all chats are loaded', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Chat::factory(2)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->call('loadMore')
        ->assertSet('offset', Index::LIMIT)
        ->assertDispatched('no-more-chats');
});

it('loads more chats without dispatching no-more-chats when more chats remain', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Chat::factory(Index::LIMIT * 2)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(Index::class, ['roomId' => $room->id])
        ->call('loadMore')
        ->assertSet('offset', Index::LIMIT)
        ->assertNotDispatched('no-more-chats');
});
