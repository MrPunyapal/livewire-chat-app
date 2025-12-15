<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('to array', function (): void {
    $chat = Chat::factory()->create()->fresh();
    expect(array_keys($chat->toArray()))->toEqual([
        'id',
        'parent_id',
        'user_id',
        'room_id',
        'message',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});

test('relationships', function (): void {
    $chat = Chat::factory()
        ->for(Chat::factory(), 'parent')
        ->create();
    $chat->favoriteUsers()->attach($chat->user->id);
    $chat->favoriteUsers()->attach(User::factory()->create()->id);

    expect($chat->user)->toBeInstanceOf(User::class)
        ->and($chat->room)->toBeInstanceOf(Room::class)
        ->and($chat->parent)->toBeInstanceOf(Chat::class)
        ->and($chat->favoriteUsers)->each()->toBeInstanceOf(Collection::class)->toBeInstanceOf(User::class);
});
