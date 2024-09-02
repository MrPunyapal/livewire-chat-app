<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Component;

class Sidebar extends Component
{
    public function render(): View
    {
        // add 10 rooms to the database with the factory
        // Room::factory()->count(10)->create(); // only uncomment once
        return view('livewire.chats.sidebar', [
            'rooms' => Room::all(),
        ]);
    }
}
