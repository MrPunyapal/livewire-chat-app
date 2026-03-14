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
use App\Enums\ChatFilterEnum;
use Illuminate\Database\Eloquent\Builder;

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

    public int $offset = 0;

    public int $limit = 10;

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

        $this->limit = 10;
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

    public function loadMore(): void
    {
        $this->limit += $this->limit;
    }

    public function render(): View
    {
        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => Chat::query()
                ->where('room_id', $this->roomId)
                ->whereHas('room.users', function (Builder $query): void {
                    $query->where('users.id', auth()->id());
                })
                ->when(
                    count($this->filters) && in_array(ChatFilterEnum::Favorites->value, $this->filters, true),
                    function (Builder $query): void {
                        $query->whereHas('favoriteUsers', fn (Builder $query) => $query->where('user_id', auth()->id()));
                    }
                )
                ->latest()
                ->with('user', 'favoriteUsers')
                ->limit($this->limit)
                ->offset($this->offset)
                ->get(),
        ]);
    }
}
