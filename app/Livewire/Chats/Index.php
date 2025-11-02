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

    /** @var array<string> */
    #[Url]
    public ?array $filters = [];

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

    public function toggleFilter(string $type): void
    {
        if ($type === '' || $type === '0') {
            return;
        }

        $this->filters ??= [];

        if (in_array($type, $this->filters, true)) {
            $this->filters = array_values(array_diff($this->filters, [$type]));
        } else {
            $this->filters[] = $type;
        }

    }

    public function render(): View
    {
        return view('livewire.chats.index', [
            'room' => $this->room,
        ]);
    }
}
