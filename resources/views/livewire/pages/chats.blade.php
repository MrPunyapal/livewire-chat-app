<div
    class="relative flex h-dvh max-h-dvh min-h-0 overflow-hidden bg-white dark:bg-zinc-900"
    x-data="{ roomsOpen: false }"
    x-on:open-rooms.window="roomsOpen = true"
    x-on:close-rooms.window="roomsOpen = false"
    x-on:room-selected.window="roomsOpen = false"
    x-on:keydown.escape.window="roomsOpen = false"
>
    <div
        class="fixed inset-0 z-40 bg-zinc-950/50 lg:hidden"
        x-cloak
        x-show="roomsOpen"
        x-transition.opacity
        x-on:click="roomsOpen = false"
    ></div>

    <livewire:rooms />

    <livewire:chats />
</div>
