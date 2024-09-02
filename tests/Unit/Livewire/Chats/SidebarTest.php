<?php

declare(strict_types=1);
use App\Livewire\Chats\Sidebar;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

test('sidebar component contains rooms', function () {
    $user = User::factory()->create();

    Room::factory()->count(10)->create();

    Livewire::actingAs($user)
        ->test(Sidebar::class)
        ->assertViewHas('rooms', Room::all());
});

test('sidebar component withou rooms', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Sidebar::class)
        ->assertSeeHtml('No rooms found');
});
