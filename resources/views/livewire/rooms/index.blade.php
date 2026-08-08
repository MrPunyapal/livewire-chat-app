<aside
    class="fixed inset-y-0 left-0 z-50 flex h-dvh max-h-dvh min-h-0 w-80 max-w-[calc(100vw-2rem)] shrink-0 -translate-x-full flex-col border-r border-zinc-200 bg-white shadow-xl transition-transform duration-200 lg:static lg:z-auto lg:max-w-none lg:translate-x-0 lg:shadow-none dark:border-zinc-700 dark:bg-zinc-800"
    x-bind:class="roomsOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex-shrink-0 border-b border-zinc-200 px-4 py-6 dark:border-zinc-700">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Chats</h1>
            <div class="flex items-center gap-2">
                <flux:modal.trigger name="create-room">
                    <flux:button icon="plus" size="sm" variant="primary" class="shrink-0">
                        <span class="hidden sm:inline">{{ __('New Chat') }}</span>
                    </flux:button>
                </flux:modal.trigger>

                <button
                    type="button"
                    class="inline-flex size-8 cursor-pointer items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 lg:hidden dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-zinc-100"
                    x-on:click="$dispatch('close-rooms')"
                    aria-label="Hide rooms"
                >
                    <flux:icon.x-mark class="size-5" />
                </button>
            </div>
        </div>
    </div>

    <flux:modal
        name="create-room"
        class="w-full max-w-lg"
        x-on:room-created.window="$dispatch('modal-close', { name: 'create-room' })"
    >
        <div class="space-y-6">
            <div class="flex items-start gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-300">
                    <flux:icon.chat-bubble-left-right class="size-5" />
                </div>

                <div class="min-w-0">
                    <flux:heading size="lg">{{ __('Create New Room') }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                        {{ __('Start a new conversation with your team.') }}
                    </flux:text>
                </div>
            </div>

            <livewire:rooms.create />
        </div>
    </flux:modal>

    <div class="flex min-h-0 flex-1 overflow-hidden">
        <div class="flex min-h-0 flex-1 flex-col px-4 pb-4">
            <!-- search room -->
            @if ($this->rooms->isNotEmpty() || $this->search)
                <div class="my-3">
                    <flux:input
                        icon="magnifying-glass"
                        wire:model.live.debounce.500ms="search"
                        name="q"
                        placeholder="Search for rooms..."
                    />
                </div>
            @endif

            <!-- Rooms list -->
            <div class="min-h-0 flex-1 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent overflow-y-auto dark:scrollbar-thumb-gray-600">
                <div class="space-y-2">
                    @island(name: 'room-list')
                        @foreach ($this->rooms as $room)
                            <livewire:rooms.show :room="$room" :key="'room-'.$room->id" />
                        @endforeach
                    @endisland

                    @if ($offset === 0 && $this->rooms->isEmpty())
                        <div class="mt-8 flex justify-center py-10 text-center">
                            <div class="max-w-xs">
                                <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-400 dark:bg-zinc-700 dark:text-zinc-500">
                                    <flux:icon.chat-bubble-left-right class="size-7" />
                                </div>
                                <h3 class="text-lg font-semibold text-zinc-600 dark:text-zinc-300">No rooms found</h3>
                                <p class="mt-1 text-sm text-zinc-400 dark:text-zinc-500">
                                    Create your first room to start chatting with your team.
                                </p>
                            </div>
                        </div>
                    @else
                        <div
                            x-data="{
                                hasMoreRooms: true,
                            }"
                            x-on:no-more-rooms.document="hasMoreRooms = false"
                        >
                            <div x-show="hasMoreRooms" wire:intersect="loadMore" wire:island.append="room-list">
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
                            <div x-show="! hasMoreRooms" class="flex justify-center py-8">
                                <div class="flex items-center gap-3 text-zinc-400 dark:text-zinc-500">
                                    <div class="h-px w-12 flex-1 bg-zinc-200 dark:bg-zinc-600"></div>
                                    <span class="text-sm font-medium">You've reached the end</span>
                                    <div class="h-px w-12 flex-1 bg-zinc-200 dark:bg-zinc-600"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</aside>
