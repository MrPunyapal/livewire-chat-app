<?php
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});
