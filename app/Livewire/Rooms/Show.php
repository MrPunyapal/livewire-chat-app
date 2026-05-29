<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('chat:created-for-room-{room.id}')]
#[On('echo-private:chats.{room.id},.chat-created')]
#[On('echo-private:chats.{room.id},.chat-updated')]
class Show extends Component
{
    public Room $room;

    public function render(): View
    {
        return view('livewire.rooms.show');
    }
}
