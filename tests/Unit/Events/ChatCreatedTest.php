<?php

declare(strict_types=1);

use App\Events\ChatCreated;
use App\Models\Chat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Event;

it('sets the data', function (): void {
    $chat = Chat::factory()->create();
    $event = new ChatCreated($chat->id, $chat->room_id);

    expect($event->chatId)->toEqual($chat->id)
        ->and($event->roomId)->toEqual($chat->room_id)
        ->and($event->broadcastOn())->toEqual([
            new PrivateChannel('chats.'.$chat->room_id),
        ])
        ->and($event->broadcastAs())->toEqual('chat-created');
});

it('can be dispatched', function (): void {
    Event::fake();
    $chat = Chat::factory()->create();
    event(new ChatCreated($chat->id, $chat->room_id));
    Event::assertDispatched(ChatCreated::class, fn (ChatCreated $event): bool => $event->chatId === $chat->id && $event->roomId === $chat->room_id);
});
