<div
    wire:key="room-{{ $room->id }}"
    x-data="{
        isActive: false,
        init() {
            // read query param and set active based on room it
            const urlParams = new URLSearchParams(window.location.search);
            const activeRoomId = urlParams.get('roomId');
            if (activeRoomId && parseInt(activeRoomId) === {{ $room->id }}) {
                this.isActive = true;
            }
        }
    }"
    class="rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md"
    x-bind:class="{
        'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 shadow-sm': isActive,
        'bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 border border-zinc-200 dark:border-zinc-600': !isActive,
    }"
    x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })"
    x-on:room-selected.window="isActive = $event.detail.id === {{ $room->id }}"
>
    <div class="flex items-center gap-3">
        <figure class="relative flex-shrink-0">
            <img
                src="{{ $room->user->profile }}"
                alt="{{ $room->user->name }}"
                class="w-12 h-12 rounded-full object-cover ring-2 ring-zinc-200 dark:ring-zinc-600"
            />
            <div
                class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 border-2 border-white dark:border-zinc-800 rounded-full"
            ></div>
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
                <!-- todo: add last message here -->
                Click to start chatting...
            </p>
        </div>
    </div>
</div>
