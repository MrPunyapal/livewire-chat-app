<?php

declare(strict_types=1);

use App\Livewire\Layout\Navigation;
use App\Models\User;
use Livewire\Livewire;

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Navigation::class);

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
