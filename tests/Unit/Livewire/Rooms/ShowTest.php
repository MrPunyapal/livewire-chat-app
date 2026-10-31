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
        ->assertSee($room->name)
        ->assertSeeHtml('room-'.$room->id)
        ->assertSeeHtml('room-selected');
});

test('room card refreshes its room after a matching room-updated event', function (): void {
    $room = Room::factory()
        ->for(User::factory(), 'user')
        ->create();

    $oldImage = 'room/'.$room->id.'/old-image.png';
    $room->update(['image' => $oldImage]);

    $component = Livewire::test(Show::class, ['room' => $room->fresh()]);

    $newImage = 'room/'.$room->id.'/new-image.png';
    $room->update(['image' => $newImage]);

    $component
        ->dispatch('room-updated', roomId: $room->id)
        ->assertSeeHtml('src="'.$newImage.'"');
});

test('room card ignores a room-updated event for another room', function (): void {
    $room = Room::factory()
        ->for(User::factory(), 'user')
        ->create();

    $oldImage = 'room/'.$room->id.'/old-image.png';
    $room->update(['image' => $oldImage]);

    $otherRoom = Room::factory()->create();
    $component = Livewire::test(Show::class, ['room' => $room->fresh()]);

    $newImage = 'room/'.$room->id.'/new-image.png';
    $room->update(['image' => $newImage]);

    $component->instance()->refreshRoom($otherRoom->id);

    expect($component->instance()->room->getRawOriginal('image'))
        ->toBe($oldImage);
});
