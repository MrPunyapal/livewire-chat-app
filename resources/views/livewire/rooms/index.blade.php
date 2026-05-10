<aside
    class="fixed inset-y-0 left-0 z-50 flex h-dvh max-h-dvh min-h-0 w-80 max-w-[calc(100vw-2rem)] shrink-0 -translate-x-full flex-col border-r border-zinc-200 bg-white shadow-xl transition-transform duration-200 dark:border-zinc-700 dark:bg-zinc-800 lg:static lg:z-auto lg:max-w-none lg:translate-x-0 lg:shadow-none"
    x-bind:class="roomsOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex-shrink-0 px-4 py-6 border-b border-zinc-200 dark:border-zinc-700">
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
                    class="inline-flex size-8 cursor-pointer items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-zinc-100 lg:hidden"
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
            @if ($rooms->isNotEmpty() || $search)
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
            <div
                class="min-h-0 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent dark:scrollbar-thumb-gray-600"
            >
                <div class="space-y-2">
                    @forelse ($rooms as $room)
                        <div
                            @class([
                                'rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md',
                                'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 shadow-sm' =>
                                    $room->id == $activeRoomId,
                                'bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 border border-zinc-200 dark:border-zinc-600' =>
                                    $room->id != $activeRoomId,
                            ])
                            x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })"
                        >
                            <div class="flex items-center gap-3">
                                <figure class="relative flex-shrink-0">
                                    <img
                                        src="{{ $room->user->profile }}"
                                        alt="{{ $room->user->name }}"
                                        class="w-12 h-12 rounded-full object-cover ring-2 ring-zinc-200 dark:ring-zinc-600"
                                    />
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 border-2 border-white dark:border-zinc-800 rounded-full">
                                    </div>
                                </figure>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3
                                            class="font-semibold text-zinc-900 dark:text-zinc-100 truncate text-sm"
                                            title="{{ $room->name }}"
                                        >
                                            {{ $room->name }}
                                        </h3>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 flex-shrink-0 ml-2">
                                            {{ $room->created_at->diffForHumans(short: true) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                        Click to start chatting...
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="mt-4 bg-white dark:bg-zinc-700 rounded-xl p-6 text-center border border-zinc-200 dark:border-zinc-600">
                            <div class="text-zinc-400 dark:text-zinc-500 mb-2">
                                <svg
                                    class="w-12 h-12 mx-auto"
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
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm">No rooms found</p>
                            <p class="text-zinc-400 dark:text-zinc-500 text-xs mt-1">Create your first room to get
                                started</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</aside>
