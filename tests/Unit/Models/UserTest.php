<?php

declare(strict_types=1);

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('to array', function () {
    $user = User::factory()->create()->fresh();
    expect(array_keys($user->toArray()))->toEqual([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

test('relationships', function () {
    $user = User::factory()
        ->has(Room::factory()->count(3))
        ->create();

    expect($user->rooms)->toBeInstanceOf(Collection::class);
    expect($user->rooms)->each->toBeInstanceOf(Room::class);
});

test('attributes', function () {
    $user = User::factory()->create();

    expect($user->profile)->toBeUrl();
});
