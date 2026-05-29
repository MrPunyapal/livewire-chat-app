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
    class="cursor-pointer rounded-xl p-4 transition-all duration-200 hover:shadow-md"
    x-bind:class="{
        'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 shadow-sm': isActive,
        'bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 border border-zinc-200 dark:border-zinc-600':
            ! isActive,
    }"
    x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })"
    x-on:room-selected.window="isActive = $event.detail.id === {{ $room->id }}"
>
    <div class="flex items-center gap-3">
        <figure class="relative flex-shrink-0">
            <img
                src="{{ $room->user->profile }}"
                alt="{{ $room->user->name }}"
                class="h-12 w-12 rounded-full object-cover ring-2 ring-zinc-200 dark:ring-zinc-600"
            />
            <div class="absolute -right-0.5 -bottom-0.5 h-4 w-4 rounded-full border-2 border-white bg-green-400 dark:border-zinc-800"></div>
        </figure>

        <div class="min-w-0 flex-1">
            <div class="mb-1 flex items-center justify-between">
                <h3 class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100" title="{{ $room->name }}">
                    {{ $room->name }}
                </h3>
                <span class="ml-2 flex-shrink-0 text-xs text-zinc-500 dark:text-zinc-400">
                    {{ $room->created_at->diffForHumans(short: true) }}
                </span>
            </div>
            <p class="truncate text-sm text-zinc-500 dark:text-zinc-400">{{ $room->lastChat?->message }}</p>
        </div>
    </div>
</div>
