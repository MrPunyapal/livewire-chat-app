<?php

declare(strict_types=1);

use App\Models\Room;
use App\Models\User;

test('to array', function () {
    $room = Room::factory()->create()->fresh();
    expect(array_keys($room->toArray()))->toEqual([
        'id',
        'name',
        'description',
        'user_id',
        'created_at',
        'updated_at',
    ]);
});

test('relationships', function () {
    $room = Room::factory()->create();

    expect($room->user)->toBeInstanceOf(User::class);
});
