<div
    class="flex flex-col-reverse gap-6 px-2"
    id="list-chats-{{ $offset }}"
>
    @foreach ($chats as $chat)
        <livewire:chats.show
            :chat="$chat"
            :key="'chat-' . $chat->id"
        />
    @endforeach

    @if ($offset === 0 && $chats->isEmpty())
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
                <h3 class="text-lg font-medium text-gray-400 dark:text-gray-500 mb-1">No messages yet</h3>
                <p class="text-gray-400 dark:text-gray-500 text-sm">Be the first to send a message in this room!</p>
            </div>
        </div>
    @endif

    @if ($chats->count() === $limit)
        <livewire:chats.list-chats
            :roomId="$roomId"
            :offset="$offset + $limit"
            :key="'list-chats-' . $roomId . '-' . $offset + $limit"
            lazy
        />
    @else
        <div class="flex justify-center py-8">
            <div class="flex items-center gap-3 text-gray-400 dark:text-gray-500">
                <div class="h-px bg-gray-200 dark:bg-gray-600 flex-1 w-12"></div>
                <span class="text-sm font-medium">You've reached the beginning</span>
                <div class="h-px bg-gray-200 dark:bg-gray-600 flex-1 w-12"></div>
            </div>
        </div>
    @endif

    @script
        <script>
            $wire.on('chats:loaded', (e) => {
                const mainContainer = 'list-chats-0';
                const currentContainer = 'list-chats-' + $wire.offset;

                requestAnimationFrame(() => {
                    const currentElement = document.getElementById(currentContainer);
                    const mainElement = document.getElementById(mainContainer);
                    while (currentElement.firstChild) {
                        mainElement.appendChild(currentElement.firstChild);
                    }
                });
            });
        </script>
    @endscript
</div>
