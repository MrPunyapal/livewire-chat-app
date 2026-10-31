<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read Collection<array-key, Room> $rooms
 */
class Index extends Component
{
    const int LIMIT = 10;

    #[Url(as: 'q')]
    public ?string $search = null;

    public int $offset = 0;

    #[On('room-created')]
    public function refresh(): void
    {
        $this->offset = 0;

        $this->js(<<<'JS'
            $wire.$island('room-list').$refresh();
        JS);
    }

    public function updatedSearch(string $value): void
    {
        $this->offset = 0;

        $this->js(<<<'JS'
            $wire.$island('room-list').$refresh();
        JS);
    }

    /**
     * @return Collection<array-key, Room>
     */
    #[Computed()]
    public function rooms(): Collection
    {
        $search = filled($this->search) ? trim($this->search) : null;

        return Room::query()
            ->with('lastChat:chats.room_id,message')
            ->when($search, fn (Builder $query) => $query->whereLike('name', sprintf('%%%s%%', $search)))
            ->whereRelation('users', 'users.id', auth()->id())
            ->latest()
            ->limit(self::LIMIT)
            ->offset($this->offset)
            ->get();
    }

    public function loadMore(): void
    {
        $this->offset += self::LIMIT;

        if ($this->rooms->count() < self::LIMIT) {
            $this->dispatch('no-more-rooms');
        }
    }

    public function render(): View
    {
        return view('livewire.rooms.index');
    }
}
