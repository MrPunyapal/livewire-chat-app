<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Room $room;

    public bool $isActive = false;

    public function render(): View
    {
        return view('livewire.rooms.show');
    }
}
