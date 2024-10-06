<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('room-created')]
class Sidebar extends Component
{
    public function render(): View
    {
        return view('livewire.chats.sidebar', [
            'rooms' => Room::query()
                ->whereRelation('users', 'users.id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }
}
