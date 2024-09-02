<?php

declare(strict_types=1);

use App\Livewire\Pages\Profile;
use App\Livewire\Profile\DeleteUserForm;
use App\Livewire\Profile\UpdatePasswordForm;
use App\Livewire\Profile\UpdateProfileInformationForm;
use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/profile');

    $response
        ->assertOk()
        ->assertSeeLivewire(Profile::class)
        ->assertSeeLivewire(UpdateProfileInformationForm::class)
        ->assertSeeLivewire(UpdatePasswordForm::class)
        ->assertSeeLivewire(DeleteUserForm::class);
});
