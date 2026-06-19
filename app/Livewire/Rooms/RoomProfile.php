<?php

namespace App\Livewire\Rooms;

use App\Models\Member;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class RoomProfile extends Component
{
    public int $roomId;

    public function render()
    {
        $room = Room::query()->findOrFail($this->roomId);
        $members = $room->users;
        return view('livewire.rooms.room-profile',[
            'room' => $room,
            'members' => $members
        ]);
    }
}
