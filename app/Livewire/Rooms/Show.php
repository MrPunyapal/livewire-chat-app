<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Room $room;

    #[On('room-updated')]
    public function refreshRoom(int $roomId): void
    {
        if ($this->room->id !== $roomId) {
            return;
        }

        $this->room->refresh();
    }

    public function render(): View
    {
        return view('livewire.rooms.show');
    }
}
