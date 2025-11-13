<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Enums\ChatFilterEnum;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\Features\SupportEvents\Event;

class ListChats extends Component
{
    public int $roomId;

    public int $limit = 10;

    public int $offset = 0;

    /** @var array<string> */
    #[Reactive]
    public array $filters = [];

    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="flex items-center justify-center w-full h-full">
                <div class="animate-pulse">
                    <div class="flex space-x-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full"></div>
                        <div class="flex-1 space-y-4 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    public function render(): View
    {
        if ($this->offset > 0) {
            /**
             * @var Event $event
             */
            $event = $this->dispatch('chats:loaded');
            $event->self();
        }

        return view('livewire.chats.list-chats', [
            'chats' => Chat::query()
                ->where('room_id', $this->roomId)
                ->whereHas('room.users', function (Builder $query): void {
                    $query->where('users.id', auth()->id());
                })
                ->when(
                    count($this->filters) && in_array(ChatFilterEnum::Favorites->value, $this->filters, true),
                    function (Builder $query): void {
                        $query->whereHas('favoritedBy', fn (Builder $query) => $query->where('user_id', auth()->id()));
                    }
                )
                ->latest()
                ->with('user', 'favoritedBy')
                ->limit($this->limit)
                ->offset($this->offset)
                ->get(),
        ]);
    }
}
