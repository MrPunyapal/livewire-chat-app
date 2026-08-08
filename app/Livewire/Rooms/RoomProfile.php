<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('members-added')]
class RoomProfile extends Component
{
    public int $roomId;

    public Room $room;

    public function mount(): void
    {
        $this->room = Room::query()->findOrFail($this->roomId);

        abort_if(Gate::denies('show-roomProfile', $this->room), 403, 'You are not authorized to view this room profile.');
    }

    public function render(): Factory|View
    {
        return view('livewire.rooms.room-profile', [
            'existingMembers' => $this->room->users,
        ]);
    }
}
