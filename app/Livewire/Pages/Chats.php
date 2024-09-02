<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chats')]
class Chats extends Component
{
    public function render(): View
    {
        return view('livewire.pages.chats');
    }
}
