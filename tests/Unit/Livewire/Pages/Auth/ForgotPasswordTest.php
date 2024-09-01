<?php

declare(strict_types=1);
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Livewire\Livewire;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('reset password link is not sent to invalid email', function () {
    Notification::fake();

    Livewire::test(ForgotPassword::class)
        ->set('email', 'invalid-email@example.com')
        ->call('sendPasswordResetLink');

    Notification::assertNothingSent();
});
