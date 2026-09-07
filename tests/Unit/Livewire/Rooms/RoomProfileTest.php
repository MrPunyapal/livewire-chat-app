<?php

declare(strict_types=1);

use App\Livewire\Rooms\RoomProfile;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('room profile renders room detail with members', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory(3)->create(), relationship: 'users')
        ->create(['name' => 'Test Room']);

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->assertStatus(200)
        ->assertSee('Room info')
        ->assertSee('Test Room')
        ->assertSee($room->users->count().' members')
        ->assertSee($room->name)
        ->assertViewIs('livewire.rooms.room-profile');
});

test('room profile denies access for unauthorized users', function (): void {
    $room = Room::factory()
        ->hasAttached(User::factory()->create(), relationship: 'users')
        ->create();

    $otherUser = User::factory()->create();

    Livewire::actingAs($otherUser)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->assertStatus(403);
});

test('room profile uploads and replaces the room image', function (): void {
    Storage::fake('public');

    $room = Room::factory()->create();
    $oldImage = 'room/'.$room->id.'/old-image.png';
    Storage::disk('public')->put($oldImage, 'old image');
    $room->update(['image' => $oldImage]);

    $image = UploadedFile::fake()->image('new-image.png');

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->set('image', $image)
        ->assertHasNoErrors()
        ->assertSet('image', null)
        ->assertDispatched('room-updated');

    $room->refresh();

    expect($room->image)
        ->not->toBe($oldImage)
        ->toStartWith('room/'.$room->id.'/');

    Storage::disk('public')->assertMissing($oldImage);
    Storage::disk('public')->assertExists($room->image);
});

test('room profile does nothing when no image is uploaded', function (): void {
    Storage::fake('public');

    $room = Room::factory()->create();

    $existingImage = 'room/'.$room->id.'/existing.png';
    $room->update([
        'image' => $existingImage,
    ]);

    Storage::disk('public')->put($existingImage, 'existing image');

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->set('image')
        ->assertSet('image', null)
        ->assertNotDispatched('room-updated');

    expect($room->fresh()->getRawOriginal('image'))
        ->toBe('room/'.$room->id.'/existing.png');

    Storage::disk('public')->assertExists($room->image);
});

test('add group description for a room on room profile component', function (): void {
    $room = Room::factory()->create([
        'name' => 'Laravel Developers',
        'description' => null,
    ]);

    $description = 'A place for Laravel developers to discuss PHP, Livewire, APIs, database design & other stuff';

    expect($room->description)
        ->toBeNull();

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->set('description', $description)
        ->call('saveDescription')
        ->assertSuccessful()
        ->assertSee($description)
        ->assertDispatched('description-saved');

    $room->refresh();

    expect($room->description)
        ->not()->toBeNull();
});

test('remove room description from room profile component', function (): void {
    $room = Room::factory()->create();

    $description = $room->description;

    expect($room->description)->not()->toBeNull();

    Livewire::actingAs($room->user)
        ->test(RoomProfile::class, ['roomId' => $room->id])
        ->set('description')
        ->call('saveDescription')
        ->assertSuccessful()
        ->assertDontSee($description)
        ->assertDispatched('description-saved');

    $room->refresh();

    expect($room->description)->toBeNull();
});
