<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function (): void {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
});
test('two factor challenge redirects to login when not authenticated', function (): void {
    $response = $this->get(route('two-factor.login'));

    $response->assertRedirect(route('login'));
});
test('two factor challenge can be rendered', function (): void {
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));
});
