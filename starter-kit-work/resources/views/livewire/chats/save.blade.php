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
                class="absolute -top-28 left-0 right-0 mx-6 bg-white dark:bg-zinc-800 shadow-xl rounded-xl p-4 mb-4 border border-zinc-200 dark:border-zinc-600 border-l-4 border-l-blue-500 animate-fade-in">
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
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 truncate">{{ $replyMessage }}</p>
                    </div>
                    <button
                        wire:click="cancel"
                        class="text-zinc-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-700 flex-shrink-0"
                        title="Cancel reply"
                    >
                        <flux:icon.x-mark class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-600 p-4">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <flux:input
                        wire:model="message"
                        id="message"
                        name="message"
                        type="text"
                        autofocus
                        placeholder="Type your message here..."
                        @keydown.enter="save"
                    />
                </div>

                <div class="flex gap-2 flex-shrink-0">
                    @if ($chatId !== null)
                        <flux:button variant="primary" type="button" x-on:click="save" icon="check">
                            Update
                        </flux:button>
                        <flux:button type="button" wire:click="cancel" icon="x-mark">
                            Cancel
                        </flux:button>
                    @elseif($parentId !== null)
                        <flux:button variant="primary" type="button" x-on:click="save" icon="arrow-uturn-left">
                            Reply
                        </flux:button>
                        <flux:button type="button" wire:click="cancel" icon="x-mark">
                            Cancel
                        </flux:button>
                    @else
                        <flux:button variant="primary" type="button" x-on:click="save" icon="paper-airplane">
                            Send
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('chat:created', (e) => {
                const mainContainer = 'chat-list';
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
