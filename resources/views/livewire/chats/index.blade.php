@php
    use App\Enums\ChatFilterEnum;
@endphp
<div
    class="relative flex h-full min-h-0 w-full"
    x-data="{ showRoomProfile: false }"
    x-on:keydown.escape.window="showRoomProfile = false"
>
    <div class="flex min-h-0 flex-1 flex-col bg-white dark:bg-zinc-800">
        <!-- Chat Header -->
        <div class="shrink-0 border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800">
            @if ($room !== null)
                <div class="flex items-center justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-4">
                        <flux:tooltip content="Toggle rooms" position="bottom">
                            <flux:button
                                icon="panel-left"
                                variant="subtle"
                                class="cursor-pointer lg:hidden"
                                x-on:click="$dispatch('open-rooms')"
                                aria-label="Toggle rooms"
                            />
                        </flux:tooltip>
                        <button
                            type="button"
                            class="flex min-w-0 cursor-pointer items-center gap-4 text-left"
                            x-on:click="showRoomProfile = true"
                            aria-label="Show room info for {{ $room->name }}"
                        >
                            <figure class="relative size-10 shrink-0">
                                <img
                                    src="{{ $room->image }}"
                                    alt="{{ $room->name }}"
                                    class="size-10 w-full rounded-full object-cover ring-2 ring-zinc-200 dark:ring-zinc-600"
                                />
                                <div class="absolute -right-0.5 -bottom-0.5 h-3 w-3 rounded-full border-2 border-white bg-green-400 dark:border-zinc-800"></div>
                            </figure>
                            <div class="min-w-0">
                                <h2 class="truncate text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $room->name }}
                                </h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Active now</p>
                            </div>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Chat filter options --}}
                        <div class="relative" x-data="{ show: false }" @click.away="show = false">
                            <flux:tooltip content="Filter chats" position="bottom">
                                <flux:button
                                    icon="ellipsis-vertical"
                                    variant="subtle"
                                    class="cursor-pointer"
                                    @click="show = ! show"
                                    aria-label="Filter chats"
                                />
                            </flux:tooltip>
                            <div
                                class="absolute right-4 z-30 min-w-36 space-y-2 rounded-md border border-zinc-200 bg-zinc-50 px-2 py-4 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                                x-show="show"
                                x-transition
                            >
                                <p class="px-2 text-sm font-semibold text-zinc-500 dark:text-zinc-400">
                                    Filter chats by
                                </p>
                                {{-- filter by favorites --}}
                                <button
                                    wire:click="toggleFilter( '{{ ChatFilterEnum::Favorites->value }}' )"
                                    type="button"
                                    @class([
                                        'w-full px-2 py-1 flex font-light gap-2 items-center text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-700 text-sm cursor-pointer rounded-md',
                                        'dark:bg-zinc-700' => in_array('favorites', $filters, true),
                                    ])
                                >
                                    <span>
                                        <flux:icon.star @class([
                                            'size-4!',
                                            'text-yellow-500 fill-yellow-500' => in_array('favorites', $filters, true),
                                        ]) />
                                    </span>
                                    Favorites
                                </button>
                            </div>
                        </div>

                        {{-- Toggle Room Profile --}}
                        <flux:tooltip content="Toggle room profile" position="bottom">
                            <flux:button
                                icon="panel-right"
                                variant="subtle"
                                class="cursor-pointer"
                                x-on:click="showRoomProfile = ! showRoomProfile"
                                aria-label="Toggle room info"
                            />
                        </flux:tooltip>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3">
                    <flux:tooltip content="Toggle rooms" position="bottom">
                        <flux:button
                            icon="panel-left"
                            variant="subtle"
                            class="cursor-pointer lg:hidden"
                            x-on:click="$dispatch('open-rooms')"
                            aria-label="Toggle rooms"
                        />
                    </flux:tooltip>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-600">
                        <svg
                            class="h-5 w-5 text-zinc-400 dark:text-zinc-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-zinc-500 dark:text-zinc-400">
                            Select a room to start chatting
                        </h2>
                        <p class="text-sm text-zinc-400 dark:text-zinc-500">Choose from the rooms on the left</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Messages Area -->
        <div class="min-h-0 flex-1 overflow-hidden bg-zinc-50 dark:bg-zinc-900">
            <div @class([
                'h-full min-h-0 flex-col-reverse overflow-y-auto px-6 py-4 scrollbar-thin scrollbar-thumb-zinc-300 scrollbar-track-transparent dark:scrollbar-thumb-zinc-600',
                'flex' => $room !== null,
                'hidden' => $room === null,
            ])>
                <div class="flex flex-col-reverse gap-6 px-2" id="chat-list">
                    @island(name: 'chat-list')
                        @if ($this->room !== null)
                            @foreach ($this->chats as $chat)
                                <livewire:chats.show :chat="$chat" :key="'chat-'.$chat->id" />
                            @endforeach
                        @endif
                    @endisland

                    @if ($room !== null && $offset === 0 && $this->chats->isEmpty())
                        <div class="flex items-center justify-center py-12" id="not-chats-found">
                            <div class="max-w-sm text-center">
                                <svg
                                    class="mx-auto mb-4 h-16 w-16 text-zinc-300 dark:text-zinc-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    ></path>
                                </svg>
                                <h3 class="mb-1 text-lg font-medium text-zinc-400 dark:text-zinc-500">
                                    No messages yet
                                </h3>
                                <p class="text-sm text-zinc-400 dark:text-zinc-500">
                                    Be the first to send a message in this room!
                                </p>
                            </div>
                        </div>
                    @elseif ($room !== null)
                        <div
                            x-data="{
                                hasMoreChats: true,
                            }"
                            x-on:no-more-chats.document="hasMoreChats = false"
                        >
                            <div x-show="hasMoreChats" wire:intersect="loadMore" wire:island.append="chat-list">
                                <div class="flex h-full w-full items-center justify-center">
                                    <div class="animate-pulse">
                                        <div class="flex space-x-4">
                                            <div class="h-12 w-12 rounded-full bg-zinc-200"></div>
                                            <div class="flex-1 space-y-4 py-1">
                                                <div class="h-4 w-3/4 rounded bg-zinc-200"></div>
                                                <div class="space-y-2">
                                                    <div class="h-4 rounded bg-zinc-200"></div>
                                                    <div class="h-4 w-5/6 rounded bg-zinc-200"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="! hasMoreChats" class="flex justify-center py-8">
                                <div class="flex items-center gap-3 text-zinc-400 dark:text-zinc-500">
                                    <div class="h-px w-12 flex-1 bg-zinc-200 dark:bg-zinc-600"></div>
                                    <span class="text-sm font-medium">You've reached the beginning</span>
                                    <div class="h-px w-12 flex-1 bg-zinc-200 dark:bg-zinc-600"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($room === null)
                <!-- Empty state -->
                <div class="flex h-full items-center justify-center">
                    <div class="max-w-md text-center">
                        <svg
                            class="mx-auto mb-6 h-20 w-20 text-zinc-300 dark:text-zinc-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            ></path>
                        </svg>
                        <h3 class="mb-2 text-xl font-semibold text-zinc-500 dark:text-zinc-400">Welcome to Chat</h3>
                        <p class="mb-6 text-zinc-400 dark:text-zinc-500">
                            Select a room from the sidebar to start a conversation, or create a new room to begin
                            chatting.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Message Input Area -->
        @if ($room !== null)
            <div class="shrink-0 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800">
                <livewire:chats.save :roomId="$room->id" key="save-chat-{{ $room->id }}" />
            </div>
        @endif
    </div>

    {{-- Mobile backdrop --}}
    <div
        class="fixed inset-0 z-40 bg-zinc-950/50 xl:hidden"
        x-cloak
        x-show="showRoomProfile"
        x-transition.opacity
        x-on:click="showRoomProfile = false"
    ></div>

    {{-- Room Profile Sidebar (Slide-over drawer on mobile/tablet, smooth expandable column on desktop) --}}
    @if ($this->room !== null)
        <section
            class="fixed inset-y-0 right-0 z-50 flex h-dvh max-h-dvh min-h-0 w-full flex-col overflow-y-auto bg-white shadow-2xl transition-all duration-300 ease-in-out sm:max-w-md xl:static xl:z-auto xl:h-full xl:max-w-none xl:overflow-x-hidden xl:shadow-none xl:transition-[width] dark:bg-zinc-800"
            :class="showRoomProfile
                ? 'translate-x-0 xl:w-96 xl:border-l xl:border-zinc-200 xl:dark:border-zinc-700'
                : 'translate-x-full xl:translate-x-0 xl:w-0 xl:border-l-0! xl:pointer-events-none pointer-events-none'"
            x-cloak
        >
            <div class="flex h-full w-full shrink-0 flex-col xl:w-96">
                <livewire:rooms.room-profile :roomId="$room->id" :key="'room-profile-'.$room->id" lazy />
            </div>
        </section>
    @endif

    @script
        <script>
            $wire.on('room-closed', (e) => {
                window.Echo.leave(`chats.${e.roomId}`);
            });
        </script>
    @endscript
</div>
