@php
    use App\Enums\ChatFilterEnum;
@endphp
<div class="bg-white dark:bg-gray-800 flex-1 flex flex-col">
    <!-- Chat Header -->
    <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        @if ($room !== null)
            <div class="flex justify-between">
                <div class="flex gap-4">
                    <figure class="relative flex-shrink-0">
                        <img
                            src="{{ $room->user->profile }}"
                            alt="{{ $room->user->name }}"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600"
                        />
                        <div
                            class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full">
                        </div>
                    </figure>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $room->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Active now</p>
                    </div>
                </div>
                {{-- Chat filter options --}}
                <div
                    class="relative"
                    x-data="{ show: false }"
                    @click.away="show = false"
                >
                    <button
                        type="button"
                        class="px-4 py-2 cursor-pointer"
                        @click="show = !show"
                    >
                        <x-icons.ellipsis-vertical />
                    </button>
                    <div
                        class="absolute z-30 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 min-w-36 right-4 rounded-md shadow-lg space-y-2 py-4 px-2"
                        x-show="show"
                        x-transition
                    >
                        <p class="font-semibold text-gray-500 dark:text-gray-400 text-sm px-2">Filter chats by</p>
                        {{-- filter by favorites --}}
                        <button
                            wire:click="toggleFilter( '{{ ChatFilterEnum::Favorites->value }}' )"
                            type="button"
                            @class([
                                'w-full px-2 py-1 flex font-light gap-2 items-center text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 text-sm cursor-pointer rounded-md',
                                'dark:bg-gray-700' => in_array('favorites', $filters),
                            ])
                        >
                            <span>
                                <x-icons.star @class([
                                    'size-4!',
                                    'text-yellow-500 fill-yellow-500' => in_array('favorites', $filters),
                                ]) />
                            </span>
                            Favorites
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="flex gap-3">
                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                    <svg
                        class="w-5 h-5 text-gray-400 dark:text-gray-500"
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
                    <h2 class="text-xl font-semibold text-gray-500 dark:text-gray-400">
                        Select a room to start chatting
                    </h2>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Choose from the rooms on the left</p>
                </div>
            </div>
        @endif
    </div>


    <!-- Messages Area -->
    <div class="flex-1 overflow-hidden bg-gray-50 dark:bg-gray-900">
        @if ($room !== null)
            <div
                class="h-full flex flex-col-reverse overflow-y-auto px-6 py-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                <div class="flex flex-col-reverse gap-6 px-2" id="chat-list">
                    @island(name: 'chat-list')
                        @foreach ($this->chats as $chat)
                            <livewire:chats.show
                                :chat="$chat"
                                :key="'chat-' . $chat->id"
                            />
                        @endforeach
                    @endisland

                    @if ($offset === 0 && $this->chats->isEmpty())
                        <div
                            class="flex justify-center items-center py-12"
                            id="not-chats-found"
                        >
                            <div class="text-center max-w-sm">
                                <svg
                                    class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4"
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
                                <h3 class="text-lg font-medium text-gray-400 dark:text-gray-500 mb-1">No messages yet
                                </h3>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Be the first to send a message in
                                    this room!</p>
                            </div>
                        </div>
                    @else
                        <div
                            x-data="{
                                hasMoreChats: true,
                            }"
                            x-on:no-more-chats.document="hasMoreChats = false"
                        >
                            <div
                                x-show="hasMoreChats"
                                wire:intersect='loadMore'
                                wire:island.append="chat-list"
                            >
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
                            </div>
                            <div
                                x-show="!hasMoreChats"
                                class="flex justify-center py-8"
                            >
                                <div class="flex items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <div class="h-px bg-gray-200 dark:bg-gray-600 flex-1 w-12"></div>
                                    <span class="text-sm font-medium">You've reached the beginning</span>
                                    <div class="h-px bg-gray-200 dark:bg-gray-600 flex-1 w-12"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="h-full flex items-center justify-center">
                <div class="text-center max-w-md">
                    <svg
                        class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-6"
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
                    <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-400 mb-2">Welcome to Chat</h3>
                    <p class="text-gray-400 dark:text-gray-500 mb-6">Select a room from the sidebar to start a
                        conversation, or create a new room to begin chatting.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input Area -->
    @if ($room !== null)
        <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
            <livewire:chats.save
                :roomId="$room->id"
                key="save-chat-{{ $room->id }}"
            />
        </div>
    @endif
    @script
        <script>
            $wire.on('room-closed', (e) => {
                window.Echo.leave(`chats.${e.roomId}`);
            });
        </script>
    @endscript
</div>
