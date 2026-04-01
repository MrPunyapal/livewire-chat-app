<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
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

    public function render(): View
    {
        $search = $this->search ? trim($this->search) : null;

        return view('livewire.rooms.index', [
            'rooms' => Room::query()
                ->when($search, fn (Builder $query) => $query->whereLike('name', sprintf('%%%s%%', $search)))
                ->whereRelation('users', 'users.id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }
}
