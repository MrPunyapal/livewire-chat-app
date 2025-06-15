<div class="relative">
    <div
        class="hidden"
        id="created-chat"
    >
        @if ($createdChat)
            <livewire:chats.show
                :chat="$createdChat"
                :key="'chat-' . $createdChat->id"
            />
        @endif
    </div>

    <div
        x-data="saveChat"
        class="relative"
    >
        @if ($parentId)
            <div
                class="absolute -top-28 left-0 right-0 mx-6 bg-white dark:bg-gray-800 shadow-xl rounded-xl p-4 mb-4 border border-gray-200 dark:border-gray-600 border-l-4 border-l-blue-500 animate-fade-in">
                <div class="flex justify-between items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <svg
                                class="w-4 h-4 text-blue-500 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"
                                ></path>
                            </svg>
                            <span class="font-medium text-blue-600 dark:text-blue-400 text-sm">Replying to:</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $replyMessage }}</p>
                    </div>
                    <button
                        wire:click="cancel"
                        class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 flex-shrink-0"
                        title="Cancel reply"
                    >
                        <x-icons.close class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-600 p-4">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <x-text-input
                        wire:model="message"
                        id="message"
                        name="message"
                        type="text"
                        autofocus
                        class="w-full bg-gray-50 dark:bg-gray-700 rounded-xl px-4 py-3 border-0 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                        placeholder="Type your message here..."
                        @keydown.enter="save"
                        style="min-height: 44px;"
                    />
                </div>

                <div class="flex gap-2 flex-shrink-0">
                    @if ($chatId !== null)
                        <button
                            type="button"
                            x-on:click="save"
                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            Update
                        </button>

                        <button
                            type="button"
                            wire:click="cancel"
                            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line
                                    x1="18"
                                    y1="6"
                                    x2="6"
                                    y2="18"
                                ></line>
                                <line
                                    x1="6"
                                    y1="6"
                                    x2="18"
                                    y2="18"
                                ></line>
                            </svg>
                            Cancel
                        </button>
                    @elseif($parentId !== null)
                        <button
                            type="button"
                            x-on:click="save"
                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"
                                />
                            </svg>
                            Reply
                        </button>
                        <button
                            type="button"
                            wire:click="cancel"
                            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line
                                    x1="18"
                                    y1="6"
                                    x2="6"
                                    y2="18"
                                ></line>
                                <line
                                    x1="6"
                                    y1="6"
                                    x2="18"
                                    y2="18"
                                ></line>
                            </svg>
                            Cancel
                        </button>
                    @else
                        <button
                            type="button"
                            x-on:click="save"
                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M22 2L11 13"></path>
                                <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                            </svg>
                            Send
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('chat:created', (e) => {
                const mainContainer = 'list-chats-0';
                const currentContainer = 'created-chat';

                requestAnimationFrame(() => {
                    document.getElementById("not-chats-found")?.remove();
                    const currentElement = document.getElementById(currentContainer);
                    const mainElement = document.getElementById(mainContainer);
                    while (currentElement.firstChild) {
                        mainElement.prepend(currentElement.firstChild);
                    }
                });
            });
        </script>
    @endscript
</div>
