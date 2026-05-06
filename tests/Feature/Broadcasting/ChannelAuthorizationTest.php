<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\Room;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'test-key',
        'broadcasting.connections.reverb.secret' => 'test-secret',
        'broadcasting.connections.reverb.app_id' => 'test-app',
    ]);

    require base_path('routes/channels.php');
});

it('authorizes room members for chat channels', function () {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    Member::factory()->create([
        'room_id' => $room->id,
        'user_id' => $user->id,
    ]);

    assertDatabaseHas('members', [
        'room_id' => $room->id,
        'user_id' => $user->id,
    ]);

    expect($user->rooms()->whereKey($room->id)->exists())->toBeTrue();

    actingAs($user)
        ->post('/broadcasting/auth', [
            'channel_name' => 'private-chats.'.$room->id,
            'socket_id' => '1234.5678',
        ])
        ->assertSuccessful();
});

it('forbids non-members from chat channels', function () {
    $member = User::factory()->create();
    $room = Room::factory()->create();

    Member::factory()->create([
        'room_id' => $room->id,
        'user_id' => $member->id,
    ]);

    $outsider = User::factory()->create();

    actingAs($outsider)
        ->post('/broadcasting/auth', [
            'channel_name' => 'private-chats.'.$room->id,
            'socket_id' => '1234.5678',
        ])
        ->assertForbidden();
});

it('builds a profile avatar url accessor', function () {
    $user = User::factory()->make([
        'name' => 'Jane Doe',
    ]);

    expect($user->profile)
        ->toBeString()
        ->toContain('https://ui-avatars.com/api/?name=Jane+Doe')
        ->toContain('color=7F9CF5')
        ->toContain('background=EBF4FF');
});
