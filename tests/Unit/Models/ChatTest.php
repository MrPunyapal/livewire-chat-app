<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Room;
use App\Models\User;

test('to array', function () {
    $chat = Chat::factory()->create()->fresh();
    expect(array_keys($chat->toArray()))->toEqual([
        'id',
        'user_id',
        'room_id',
        'message',
        'created_at',
        'updated_at',
    ]);
});

test('relationships', function () {
    $chat = Chat::factory()
        ->create();

    expect($chat->user)->toBeInstanceOf(User::class);
    expect($chat->room)->toBeInstanceOf(Room::class);
});
