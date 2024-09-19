<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
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
        // get the current auth user
        $user = auth()->user(); 

        // stop phpstan related errors by assert $user as instance of User model 
        assert($user instanceof User);

        // add 10 chats to the database with the factory

        // Chat::factory()->count(10)->create(['room_id' =>  $user->rooms()->create(['name' => 'example room'])->id]); // only uncomment once

        $this->roomId = $user->rooms()->latest()->first()?->id;

        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->room !== null ? Chat::query()->where('room_id', $this->roomId)->get() : [],
        ]);
    }
}
