<?php

declare(strict_types=1);

use App\Livewire\Rooms\Show;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('room card renders room details', function (): void {
    $room = Room::factory()
        ->for(User::factory(), 'user')
        ->create([
            'name' => 'Design review',
        ]);

    Livewire::test(Show::class, ['room' => $room])
        ->assertSee('Design review')
        ->assertSee($room->user->name)
        ->assertSeeHtml('room-'.$room->id)
        ->assertSeeHtml('room-selected');
});

test('room card marks active room', function (): void {
    $room = Room::factory()
        ->for(User::factory(), 'user')
        ->create([
            'name' => 'Planning',
        ]);

    Livewire::test(Show::class, ['room' => $room, 'activeRoomId' => $room->id])
        ->assertSeeHtml('bg-blue-50')
        ->assertSeeHtml('border-2 border-blue-200');
});
