<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\Room;
use App\Models\User;

test('to array', function (): void {
    $member = Member::factory()->create()->fresh();
    expect(array_keys($member->toArray()))->toEqual([
        'id',
        'room_id',
        'user_id',
        'created_at',
        'updated_at',
    ]);
});

test('relationships', function (): void {
    $member = Member::factory()
        ->create();

    expect($member->room)->toBeInstanceOf(Room::class)
        ->and($member->user)->toBeInstanceOf(User::class);
});
