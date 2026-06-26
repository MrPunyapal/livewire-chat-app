<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class RoomProfile extends Component
{
    public int $roomId;

    public Room $room;

    /**
     * @var Collection<int, User>
     */
    public Collection $existingMembers;

    public function mount(): void
    {
        $this->room = Room::query()->findOrFail($this->roomId);
        $this->existingMembers = $this->room->users;
    }

    public function render(): Factory|View
    {
        return view('livewire.rooms.room-profile');
    }
}
