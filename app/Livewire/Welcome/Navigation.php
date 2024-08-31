<?php

declare(strict_types=1);

namespace App\Livewire\Welcome;

use Illuminate\View\View;
use Livewire\Component;

class Navigation extends Component
{
    public function render(): View
    {
        return view('livewire.welcome.navigation');
    }
}
