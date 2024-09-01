<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        if (Auth::user() === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.profile.delete-user-form');
    }
}
