<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read ?Room $room
 */
class Index extends Component
{
    #[Locked]
    #[Url]
    public ?int $roomId = null;

    #[Computed]
    public function room(): ?Room
    {
        return $this->roomId === null ? null : Room::query()
            ->whereRelation('users', 'users.id', auth()->id())
            ->find($this->roomId);
    }

    #[On('room-selected')]
    public function selectRoom(int $id): void
    {
        $this->roomId = $id;
    }

    public function render(): View
    {
        // add 10 chats to the database with the factory
        // Chat::factory()->count(10)->create(['room_id' => $this->roomId]); // only uncomment once
        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->room !== null ? Chat::query()->where('room_id', $this->roomId)->get() : [],
        ]);
    }
}
