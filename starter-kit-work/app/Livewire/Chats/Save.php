<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Events\ChatCreated;
use App\Events\ChatUpdated;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Save extends Component
{
    #[Locked]
    #[Reactive]
    public int $roomId;

    #[Validate('required|string')]
    public string $message;

    public string $replyMessage = '';

    #[Locked]
    public ?int $chatId = null;

    #[Locked]
    public ?int $parentId = null;

    private ?Chat $createdChat = null;

    #[On('chat-editing')]
    public function startEditing(int $chatId, string $message): void
    {
        $this->chatId = $chatId;
        $this->message = $message;
        $this->parentId = null;
        $this->replyMessage = '';
    }

    #[On('chat-replying')]
    public function startReplying(int $chatId, string $message): void
    {
        $this->parentId = $chatId;
        $this->replyMessage = $message;
        $this->message = '';
        $this->chatId = null;
    }

    public function save(): void
    {
        $this->validate();

        $room = Room::query()->findOrFail($this->roomId);

        $memberExists = $room->users()
            ->where('user_id', auth()->id())
            ->exists();

        Gate::denyIf(! $memberExists, 'You are not a member of this room.');

        if ($this->parentId !== null && $this->parentId !== 0) {
            $chat = $room->chats()->create([
                'user_id' => auth()->id(),
                'message' => $this->pull('message'),
                'parent_id' => $this->parentId,
            ]);

            broadcast(new ChatCreated(
                chatId: $chat->id,
                roomId: $this->roomId,
            ))->toOthers();

            $this->createdChat = $chat;
            $this->dispatch('chat:created');

            $this->parentId = null;
            $this->replyMessage = '';

            return;
        }

        if ($this->chatId !== null && $this->chatId !== 0) {
            $chat = $room->chats()->findOrFail($this->chatId);
            Gate::denyIf($chat->user_id !== auth()->id(), 'You are not the owner of this chat.');

            $chat->update([
                'message' => $this->pull('message'),
            ]);

            broadcast(new ChatUpdated(
                chatId: $chat->id,
                roomId: $this->roomId,
            ))->toOthers();

            $this->dispatch('chat:updated.'.$this->chatId);

            $this->chatId = null;

            return;
        }

        $chat = $room->chats()->create([
            'user_id' => auth()->id(),
            'message' => $this->pull('message'),
        ]);

        broadcast(new ChatCreated(
            chatId: $chat->id,
            roomId: $this->roomId,
        ))->toOthers();

        $this->createdChat = $chat;

        $this->dispatch('chat:created');
    }

    /**
     * @param  array{roomId: int, chatId: int}  $event
     */
    #[On('echo-private:chats.{roomId},.chat-created')]
    public function chatCreated(array $event): void
    {
        $this->createdChat = Chat::query()
            ->whereKey($event['chatId'])
            ->first();

        $this->dispatch('chat:created');
    }

    /**
     * @param  array{roomId: int, chatId: int}  $event
     */
    #[On('echo-private:chats.{roomId},.chat-updated')]
    public function chatUpdated(array $event): void
    {
        $this->dispatch('chat:updated.'.$event['chatId']);
    }

    public function cancel(): void
    {
        $this->message = '';
        $this->chatId = null;
        $this->parentId = null;
        $this->replyMessage = '';
    }

    public function render(): View
    {
        return view('livewire.chats.save', [
            'createdChat' => $this->createdChat,
        ]);
    }
}
