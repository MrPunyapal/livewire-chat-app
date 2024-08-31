<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use Illuminate\View\View;
use Livewire\Component;

class Navigation extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.layout.navigation');
    }
}
