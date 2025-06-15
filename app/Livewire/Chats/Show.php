<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Events\ChatUpdated;
use App\Models\Chat;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('chat:updated.{chat.id}')]
class Show extends Component
{
    public Chat $chat;

    public function edit(): void
    {
        $this->dispatch('chat-editing', chatId: $this->chat->id, message: $this->chat->message);
    }

    public function delete(): void
    {
        abort_unless($this->isCurrentUser(), 403, 'You are not authorized to delete this chat.');
        $this->chat->touch('deleted_at');
        $this->dispatch('chat:deleted', chatId: $this->chat->id);

        broadcast(new ChatUpdated(
            chatId: $this->chat->id,
            roomId: $this->chat->room_id,
        ))->toOthers();

        $this->dispatch('chat:updated.'.$this->chat->id);
    }

    public function reply(): void
    {
        $this->dispatch('chat-replying', chatId: $this->chat->id, message: $this->chat->message);
    }

    public function toggleFavourite(): void
    {
        $this->chat->favouritedBy()->toggle(auth()->id());
    }

    public function render(): View
    {
        if ($this->chat->parent_id !== null) {
            $this->chat->load('parent');
        }

        return view('livewire.chats.show', [
            'chat' => $this->chat,
            'isCurrentUser' => $this->isCurrentUser(),
        ]);
    }

    /**
     * @return array<array-key, string>
     */
    protected function getListeners(): array
    {
        if ($this->chat->parent_id !== null) {
            return [
                'chat:updated.'.$this->chat->parent_id => '$refresh',
            ];
        }

        return [];
    }

    private function isCurrentUser(): bool
    {
        return $this->chat->user->id === auth()->id();
    }
}
