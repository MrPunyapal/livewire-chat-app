<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Enums\ChatFilterEnum;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read ?Room $room
 * @property-read Collection<array-key, Chat> $chats
 */
class Index extends Component
{
    const int LIMIT = 10;

    #[Locked]
    #[Url]
    public ?int $roomId = null;

    /** @var array<string> */
    #[Url]
    public array $filters = [];

    public int $offset = 0;

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

        $this->offset = 0;

        $this->js(<<<'JS'
            $wire.$island('chat-list').$refresh()
        JS);
    }

    public function toggleFilter(string $type): void
    {
        if ($type === '' || $type === '0') {
            return;
        }

        if (in_array($type, $this->filters, true)) {
            $this->filters = array_values(array_diff($this->filters, [$type]));
        } else {
            $this->filters[] = $type;
        }

        $this->offset = 0;

        $this->js(<<<'JS'
            $wire.$island('chat-list').$refresh()
        JS);
    }

    public function loadMore(): void
    {
        $this->offset += self::LIMIT;

        if ($this->chats->count() < self::LIMIT) {
            $this->dispatch('no-more-chats');
        }
    }

    /**
     * @return Collection<array-key, Chat>
     */
    #[Computed]
    public function chats(): Collection
    {
        return Chat::query()
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
            ->limit(self::LIMIT)
            ->offset($this->offset)
            ->get();
    }

    public function render(): View
    {
        if ($this->offset > 0) {
            $this->dispatch('chats:loaded');
        }

        return view('livewire.chats.index', [
            'room' => $this->room,
        ]);
    }
}
