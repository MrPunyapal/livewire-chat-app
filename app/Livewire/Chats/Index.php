<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

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

    public ?array $filters = [];

    #[Url]
    public ?string $filterBy;

    public function mount()
    {
        $this->filters = ! empty($this->filterBy) ? explode(',', $this->filterBy) : [];
    }

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
        $this->dispatch('room-closed', roomId: $this->roomId);

        $this->roomId = $id;
    }

    public function toggleFilter($type): void
    {
        if (empty($type)) {
            return;
        }

        if (in_array($type, $this->filters, true)) {
            $key = array_search($type, $this->filters, true);
            unset($this->filters[$key]);
            info('array after unset', ['value' => $this->filters]);
            $this->filters = array_values($this->filters);
            $this->filterBy = implode(',', $this->filters);

            return;
        }
        $this->filters[] = $type;
        $this->filterBy = implode(',', $this->filters);
    }

    public function render(): View
    {
        return view('livewire.chats.index', [
            'room' => $this->room,
        ]);
    }
}
