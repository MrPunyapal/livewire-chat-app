<?php

declare(strict_types=1);

use App\Livewire\Chats\Create;
use App\Models\User;
use Livewire\Livewire;

it('can render the create chat component', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Create::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.chats.create');
});

it('validates the name field', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Create::class)
        ->set('name', '')
        ->call('store')
        ->assertHasErrors(['name', 'members']);

    Livewire::test(Create::class)
        ->set('name', 'A')
        ->call('store')
        ->assertHasErrors(['name']);

    Livewire::test(Create::class)
        ->set('name', str_repeat('A', 81))
        ->call('store')
        ->assertHasErrors(['name']);

    Livewire::test(Create::class)
        ->set('members', [])
        ->call('store')
        ->assertHasErrors(['members']);

    Livewire::test(Create::class)
        ->set('members', [999])
        ->call('store')
        ->assertHasErrors(['members.*']);
});

it('can create a chat room', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $member = User::factory()->create();

    Livewire::test(Create::class)
        ->set('name', 'Test Room')
        ->set('members', [$member->id])
        ->call('store')
        ->assertDispatched('room-created')
        ->assertDispatched('room-selected');

    $this->assertDatabaseHas('rooms', ['name' => 'Test Room']);
    $this->assertDatabaseHas('members', ['user_id' => $member->id, 'room_id' => 1]);
});

it('redirects to login page if user is not authenticated', function () {
    Livewire::test(Create::class)
        ->call('store')
        ->assertRedirect(route('login'));
});
