<?php

declare(strict_types=1);

use App\Events\ChatUpdated;
use App\Models\Chat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Event;

it('sets the data', function (): void {
    $chat = Chat::factory()->create();
    $event = new ChatUpdated($chat->id, $chat->room_id);

    expect($event->chatId)->toEqual($chat->id)
        ->and($event->roomId)->toEqual($chat->room_id)
        ->and($event->broadcastOn())->toEqual([
            new PrivateChannel('chats.'.$chat->room_id),
        ])
        ->and($event->broadcastAs())->toEqual('chat-updated');
});

it('can be dispatched', function (): void {
    Event::fake();
    $chat = Chat::factory()->create();
    event(new ChatUpdated($chat->id, $chat->room_id));
    Event::assertDispatched(ChatUpdated::class, fn (ChatUpdated $event): bool => $event->chatId === $chat->id && $event->roomId === $chat->room_id);
});
