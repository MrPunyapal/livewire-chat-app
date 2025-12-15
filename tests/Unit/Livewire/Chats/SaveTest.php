<?php

declare(strict_types=1);
use App\Events\ChatCreated;
use App\Events\ChatUpdated;
use App\Livewire\Chats\Save;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

beforeEach(function (): void {
    Event::fake();
});

it('can render the create chat component', function (): void {
    Livewire::actingAs(User::factory()->create())
        ->test(Save::class, ['roomId' => Room::factory()->create()->getKey()])
        ->assertStatus(200)
        ->assertViewIs('livewire.chats.save');
});

it('validates the message field', function (): void {
    Livewire::actingAs(User::factory()->create())
        ->test(Save::class, ['roomId' => Room::factory()->create()->getKey()])
        ->set('message', '')
        ->call('save')
        ->assertHasErrors(['message']);
});

it('checks for exception when roomId is invalid', function (): void {
    $this->actingAs(User::factory()->create());

    $this->expectException(ModelNotFoundException::class);

    Livewire::test(Save::class, ['roomId' => 123])
        ->set('message', 'test message')
        ->call('save')
        ->assertNotFound();
});

it('can create a chat', function (): void {

    $john = User::factory()->create(['name' => 'John Doe']);

    // other user
    $jane = User::factory()->create(['name' => 'Jane Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $jane->id]);

    $room->users()->attach($john->id);

    $component = Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    expect($component->invade()->createdChat)->not->toBeNull();

    $this->assertDatabaseHas('chats', ['message' => 'message from John']);

    Event::assertDispatched(ChatCreated::class, fn (ChatCreated $event): bool => $event->roomId === $room->id && $event->chatId === 1);
});

it('can not create a chat as an invalid user/member ', function (): void {

    $john = User::factory()->create(['name' => 'John Doe']);

    // other user
    $jane = User::factory()->create(['name' => 'Jane Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $jane->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertStatus(403);

    Event::assertNotDispatched(ChatCreated::class);
});

it('can edit a chat', function (): void {

    $john = User::factory()->create(['name' => 'John Doe']);

    // other user
    $jane = User::factory()->create(['name' => 'Jane Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $jane->id]);

    $room->users()->attach([$john->id, $jane->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'edited message from John')
        ->dispatch('chat-editing', chatId: 1, message: 'edited message from John')
        ->call('save')
        ->assertDispatched('chat:updated.1');

    $this->assertDatabaseHas('chats', ['message' => 'edited message from John']);

    Event::assertDispatched(ChatUpdated::class, fn (ChatUpdated $event): bool => $event->roomId === $room->id && $event->chatId === 1);
});

it('can reply to a chat', function (): void {

    $john = User::factory()->create(['name' => 'John Doe']);

    // other user
    $jane = User::factory()->create(['name' => 'Jane Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $jane->id]);

    $room->users()->attach([$john->id, $jane->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->dispatch('chat-replying', chatId: 1, message: 'reply message from John')
        ->set('message', 'reply message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    $this->assertDatabaseHas('chats', ['message' => 'reply message from John', 'parent_id' => 1]);

    Event::assertDispatched(ChatCreated::class, fn (ChatCreated $event): bool => $event->roomId === $room->id && $event->chatId === 2);
});

it('can not edit a chat as an invalid user/member ', function (): void {

    $john = User::factory()->create(['name' => 'John Doe']);

    // other user
    $jane = User::factory()->create(['name' => 'Jane Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $jane->id]);

    $room->users()->attach([$john->id, $jane->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::actingAs($jane)
        ->test(Save::class, ['roomId' => $room->id])
        ->set('message', 'edited message from John')
        ->dispatch('chat-editing', chatId: 1, message: 'edited message from John')
        ->call('save')
        ->assertStatus(403);

    Event::assertNotDispatched(ChatUpdated::class);
});

it('sets chatId and message for chat-editing event', function (): void {
    $john = User::factory()->create(['name' => 'John Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $john->id]);

    $room->users()->attach([$john->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->dispatch('chat-editing', chatId: 1, message: 'edited message from John')
        ->assertSet('chatId', 1)
        ->assertSet('message', 'edited message from John');
});

it('clears the parentId and replyMessage for chat-editing event', function (): void {
    $john = User::factory()->create(['name' => 'John Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $john->id]);

    $room->users()->attach([$john->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id, 'parentId' => 1, 'replyMessage' => 'reply message from John'])
        ->assertSet('parentId', 1)
        ->assertSet('replyMessage', 'reply message from John')
        ->dispatch('chat-editing', chatId: 1, message: 'edited message from John')
        ->assertSet('parentId', null)
        ->assertSet('replyMessage', '')
        ->assertSet('chatId', 1)
        ->assertSet('message', 'edited message from John');
});

it('sets parentId and replyMessage for chat-replying event', function (): void {
    $john = User::factory()->create(['name' => 'John Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $john->id]);

    $room->users()->attach([$john->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->dispatch('chat-replying', chatId: 1, message: 'reply message from John')
        ->assertSet('parentId', 1)
        ->assertSet('replyMessage', 'reply message from John');
});

it('clears the chatId and message for chat-replying event', function (): void {
    $john = User::factory()->create(['name' => 'John Doe']);

    $this->actingAs($john);

    $room = Room::factory()->create(['user_id' => $john->id]);

    $room->users()->attach([$john->id]);

    Livewire::test(Save::class, ['roomId' => $room->id])
        ->set('message', 'message from John')
        ->call('save')
        ->assertDispatched('chat:created');

    Livewire::test(Save::class, ['roomId' => $room->id, 'chatId' => 1, 'message' => 'message from John'])
        ->assertSet('chatId', 1)
        ->assertSet('message', 'message from John')
        ->dispatch('chat-replying', chatId: 1, message: 'reply message from John')
        ->assertSet('parentId', 1)
        ->assertSet('replyMessage', 'reply message from John')
        ->assertSet('chatId', null)
        ->assertSet('message', '');
});

it('can listen to echo events', function (): void {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $chat = $room->chats()->create([
        'user_id' => $user->id,
        'message' => 'test message',
    ]);

    $component = Livewire::actingAs($user)
        ->test(Save::class, ['roomId' => $room->id])
        ->call('chatCreated', [
            'roomId' => $room->id,
            'chatId' => $chat->id,
        ])
        ->assertDispatched('chat:created');
    expect($component->invade()->createdChat)->not->toBeNull();

    $component->call('chatUpdated', [
        'roomId' => 1,
        'chatId' => 1,
    ])
        ->assertDispatched('chat:updated.1');
});

it('can cancel the editing', function (): void {
    Livewire::actingAs(User::factory()->create())
        ->test(Save::class, [
            'roomId' => Room::factory()->create()->getKey(),
            'chatId' => 1,
            'message' => 'test message',
        ])
        ->assertSet('chatId', 1)
        ->assertSet('message', 'test message')
        ->call('cancel')
        ->assertSet('chatId', null)
        ->assertSet('message', '');
});

it('can cancel the reply', function (): void {
    Livewire::actingAs(User::factory()->create())
        ->test(Save::class, [
            'roomId' => Room::factory()->create()->getKey(),
            'parentId' => 1,
            'replyMessage' => 'test message',
        ])
        ->assertSet('parentId', 1)
        ->assertSet('replyMessage', 'test message')
        ->call('cancel')
        ->assertSet('parentId', null)
        ->assertSet('replyMessage', '');
});
