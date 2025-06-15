<?php

declare(strict_types=1);

use App\Events\ChatUpdated;
use App\Livewire\Chats\Show;
use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

beforeEach(function (): void {
    Event::fake();
});

it('can render', function (): void {
    $chat = Chat::factory()->create();

    Livewire::test(Show::class, ['chat' => $chat])
        ->assertSee($chat->message)
        ->assertSee($chat->user->name);
});

it('can edit', function (): void {
    $chat = Chat::factory()->create();

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->call('edit')
        ->assertDispatched(
            'chat-editing',
            chatId: $chat->id,
            message: $chat->message,
        );
});

it('can reply', function (): void {
    $chat = Chat::factory()->create();

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->call('reply')
        ->assertDispatched(
            'chat-replying',
            chatId: $chat->id,
            message: $chat->message,
        );
});

it('can refresh when parent chat is updated', function (): void {
    $parentChat = Chat::factory()->create();
    $chat = Chat::factory()->create([
        'parent_id' => $parentChat->id,
        'message' => 'Original message',
    ]);

    $component = Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat]);

    $parentChat->update(['message' => 'Updated message']);

    $component->assertSee('Original message')
        ->dispatch('chat:updated.'.$parentChat->id)
        ->assertSee('Updated message');
});

it('only shows the edit button if the user is the owner of the chat', function (): void {
    $chat = Chat::factory()->create();

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('wire:click="edit"');

    Livewire::actingAs(User::factory()->create())
        ->test(Show::class, ['chat' => $chat])
        ->assertDontSeeHtml('wire:click="edit"');
});

it('can delete', function (): void {
    $chat = Chat::factory()->create();

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->call('delete')
        ->assertDispatched('chat:deleted', chatId: $chat->id);

    expect($chat->fresh()->deleted_at)->not()->toBeNull();

    Event::assertDispatched(ChatUpdated::class, function (ChatUpdated $event) use ($chat): bool {
        return $event->chatId === $chat->id && $event->roomId === $chat->room_id;
    });
});

it('can not delete if the user is not the owner of the chat', function (): void {
    $chat = Chat::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(Show::class, ['chat' => $chat])
        ->call('delete')
        ->assertForbidden();
});

it('only shows the delete button if the user is the owner of the chat', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);
    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('title="Delete message"');

    Livewire::actingAs(User::factory()->create())
        ->test(Show::class, ['chat' => $chat])
        ->assertDontSeeHtml('title="Delete message"');
});

it('only shows the delete button if the chat is not deleted', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('title="Delete message"');

    $chat->touch('deleted_at');

    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->assertDontSeeHtml('title="Delete message"');
});

it('can toggle chat as favourite', function (): void {
    $user = User::factory()->create();

    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    $chat = Chat::factory()
        ->for($room)
        ->create();

    expect($chat->favouritedBy()->count())->toBe(0);

    // Toggle favourite for the first time
    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->call('toggleFavourite')
        ->assertSeeHtml('Remove from favourite chats');

    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->call('toggleFavourite')
        ->assertDontSeeHtml('Mark as favourite');

    expect($chat->favouritedBy()->count())->toBe(2);

    // Toggle again to remove from favourite chats
    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->call('toggleFavourite')
        ->assertSeeHtml('Mark as favourite');

    $chat->refresh();
    expect($chat->favouritedBy()->count())->toBe(1);
});

it('deletes message when confirmed', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->call('delete')
        ->assertDispatched('chat:deleted', chatId: $chat->id);

    expect($chat->fresh()->deleted_at)->not()->toBeNull();

    Event::assertDispatched(ChatUpdated::class, function (ChatUpdated $event) use ($chat): bool {
        return $event->chatId === $chat->id && $event->roomId === $chat->room_id;
    });
});

it('only renders delete confirmation modal if user is the owner of the chat', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    // Owner should see the modal
    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('confirm-chat-deletion-'.$chat->id)
        ->assertSeeHtml('Delete Message');

    // Non-owner should not see the modal
    Livewire::actingAs(User::factory()->create())
        ->test(Show::class, ['chat' => $chat])
        ->assertDontSeeHtml('confirm-chat-deletion-'.$chat->id)
        ->assertDontSeeHtml('Delete Message');
});

it('only renders delete confirmation modal if chat is not deleted', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    // Non-deleted chat should show modal
    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('confirm-chat-deletion-'.$chat->id)
        ->assertSeeHtml('Delete Message');

    // Deleted chat should not show modal
    $chat->touch('deleted_at');

    Livewire::actingAs($user)
        ->test(Show::class, ['chat' => $chat])
        ->assertDontSeeHtml('confirm-chat-deletion-'.$chat->id)
        ->assertDontSeeHtml('Delete Message');
});

it('renders modal with correct chat ID in the Alpine.js event listeners', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('confirm-chat-deletion-'.$chat->id)
        ->assertSeeHtml('x-on:open-modal.window')
        ->assertSeeHtml('x-on:close-modal.window');
});

it('modal contains correct action buttons', function (): void {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($chat->user)
        ->test(Show::class, ['chat' => $chat])
        ->assertSeeHtml('Cancel')
        ->assertSeeHtml('Delete')
        ->assertSeeHtml('wire:click="delete"')
        ->assertSeeHtml('confirm-chat-deletion-'.$chat->id);
});
