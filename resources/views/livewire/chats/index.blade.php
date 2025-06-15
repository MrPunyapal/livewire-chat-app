<div class="bg-white dark:bg-gray-800 flex-1 flex flex-col">
    <!-- Chat Header -->
    <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                @if ($room !== null)
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
                @else
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
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
        </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-hidden bg-gray-50 dark:bg-gray-900">
        @if ($room !== null)
            <div
                class="h-full flex flex-col-reverse overflow-y-auto px-6 py-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                <livewire:chats.list-chats
                    :roomId="$roomId"
                    :key="'list-chats-' . $roomId . '-0'"
                />
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
