<?php

declare(strict_types=1);

namespace App\View\Components;

use Override;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    #[Override]
    public function render(): View
    {
        return view('layouts.app');
    }
}
