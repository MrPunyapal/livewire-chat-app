<div class="relative">
    <div class="hidden" id="created-chat">
        @if ($createdChat)
            <livewire:chats.show :chat="$createdChat" :key="'chat-'.$createdChat->id" />
        @endif
    </div>

    <div x-data="saveChat" class="relative">
        @if ($parentId)
            <div class="animate-fade-in absolute -top-28 right-0 left-0 mx-6 mb-4 rounded-xl border border-l-4 border-zinc-200 border-l-blue-500 bg-white p-4 shadow-xl dark:border-zinc-600 dark:bg-zinc-800">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="mb-2 flex items-center gap-2">
                            <svg
                                class="h-4 w-4 flex-shrink-0 text-blue-500"
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
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Replying to:</span>
                        </div>
                        <p class="truncate text-sm text-zinc-700 dark:text-zinc-300">{{ $replyMessage }}</p>
                    </div>
                    <button
                        wire:click="cancel"
                        class="flex-shrink-0 rounded-full p-1 text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-red-500 dark:hover:bg-zinc-700"
                        title="Cancel reply"
                    >
                        <flux:icon.x-mark class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-lg dark:border-zinc-600 dark:bg-zinc-800">
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

                <div class="flex flex-shrink-0 gap-2">
                    @if ($chatId !== null)
                        <flux:button variant="primary" type="button" x-on:click="save" icon="check">
                            Update
                        </flux:button>
                        <flux:button type="button" wire:click="cancel" icon="x-mark"> Cancel </flux:button>
                    @elseif ($parentId !== null)
                        <flux:button variant="primary" type="button" x-on:click="save" icon="arrow-uturn-left">
                            Reply
                        </flux:button>
                        <flux:button type="button" wire:click="cancel" icon="x-mark"> Cancel </flux:button>
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
                    document.getElementById('not-chats-found')?.remove();
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
