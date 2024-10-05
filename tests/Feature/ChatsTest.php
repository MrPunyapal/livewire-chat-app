<?php

declare(strict_types=1);

use App\Livewire\Chats\Create;
use App\Livewire\Chats\Index;
use App\Livewire\Chats\Sidebar;
use App\Livewire\Pages\Chats;
use App\Models\User;

test('Chats page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/chats')
        ->assertSeeLivewire(Chats::class)
        ->assertSeeLivewire(Sidebar::class)
        ->assertSeeLivewire(Index::class)
        ->assertSeeLivewire(Create::class)
        ->assertOk();
});
