<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class ConfirmPassword extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        if (Auth::user() === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $this->validate([
            'password' => ['required', 'string'],
        ]);

        throw_unless(Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ]), ValidationException::withMessages([
            'password' => __('auth.password'),
        ]));

        session(['auth.password_confirmed_at' => Carbon::now()->timestamp]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.pages.auth.confirm-password');
    }
}
