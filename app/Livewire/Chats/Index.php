<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * @property-read ?Room $room
 */
class Index extends Component
{
    #[Locked]
    public ?int $roomId = null;

    #[Computed]
    public function room(): ?Room
    {
        return $this->roomId === null ? null : Room::query()->find($this->roomId);
    }

    public function render(): View
    {
        // add 10 chats to the database with the factory

        // Chat::factory()->count(10)->create(['room_id' => Room::factory()->create()->id]); // only uncomment once
        $this->roomId = 1;

        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->room !== null ? Chat::query()->where('room_id', $this->roomId)->get() : [],
        ]);
    }
}
