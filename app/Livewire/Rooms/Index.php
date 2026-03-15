<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[On('room-created')]
class Index extends Component
{
    public ?int $activeRoomId = null;

    #[Url(as: 'q')]
    public ?string $search = null;

    #[On('room-selected')]
    public function getActiveRoomId(int $id): void
    {
        $this->activeRoomId = $id;
    }

    /**
     * @return Collection<array-key, Room>
     */
    #[Computed()]
    public function rooms(): Collection
    {
        return Room::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->whereRelation('users', 'users.id', auth()->id())
            ->latest()
            ->get();
    }

    public function render(): View
    {
        return view('livewire.rooms.index');
    }
}
