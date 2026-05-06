<aside class="bg-white dark:bg-gray-800 w-16 md:w-80 border-r border-gray-200 dark:border-gray-700 flex flex-col">
    <div class="flex-shrink-0 px-4 py-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <h1 class="hidden md:block text-2xl font-bold text-gray-900 dark:text-gray-100">Chats</h1>
            <flux:modal.trigger name="create-room">
                <flux:button icon="plus" size="sm" variant="primary">
                    <span class="hidden md:inline">{{ __('New Chat') }}</span>
                </flux:button>
            </flux:modal.trigger>

            <flux:modal name="create-room" class="md:w-96" x-on:room-created.window="$dispatch('modal:close', 'create-room')">
                <flux:heading size="lg">{{ __('Create New Room') }}</flux:heading>
                <flux:subheading class="mb-4">{{ __('Start a new conversation with your team.') }}</flux:subheading>

                <livewire:rooms.create />
            </flux:modal>
        </div>
    </div>

    <div class="flex-1 overflow-hidden">
        <div class="h-full px-4 pb-4">
            <!-- Mobile chat icon -->
            <div class="flex justify-center md:hidden mb-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-8 h-8 text-blue-600 dark:text-blue-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"
                    ></path>
                </svg>
            </div>

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
                class="hidden md:block h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                <div class="space-y-2">
                    @forelse ($rooms as $room)
                        <div
                            @class([
                                'rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md',
                                'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 shadow-sm' =>
                                    $room->id == $activeRoomId,
                                'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600' =>
                                    $room->id != $activeRoomId,
                            ])
                            x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })"
                        >
                            <div class="flex items-center gap-3">
                                <figure class="relative flex-shrink-0">
                                    <img
                                        src="{{ $room->user->profile }}"
                                        alt="{{ $room->user->name }}"
                                        class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600"
                                    />
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full">
                                    </div>
                                </figure>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3
                                            class="font-semibold text-gray-900 dark:text-gray-100 truncate text-sm"
                                            title="{{ $room->name }}"
                                        >
                                            {{ $room->name }}
                                        </h3>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">
                                            {{ $room->created_at->diffForHumans(short: true) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        Click to start chatting...
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="mt-4 bg-white dark:bg-gray-700 rounded-xl p-6 text-center border border-gray-200 dark:border-gray-600">
                            <div class="text-gray-400 dark:text-gray-500 mb-2">
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
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No rooms found</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Create your first room to get
                                started</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</aside>
