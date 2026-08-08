<div
    class="relative flex h-dvh max-h-dvh min-h-0 overflow-hidden bg-white dark:bg-zinc-900"
    x-data="{ roomsOpen: false, showRoomProfile: false }"
    x-on:open-rooms.window="roomsOpen = true"
    x-on:close-rooms.window="roomsOpen = false"
    x-on:room-selected.window="roomsOpen = false"
    x-on:keydown.escape.window="roomsOpen = false; showRoomProfile = false; $dispatch('toggle-room-profile',{ showRoomProfile: false, roomId: 0 })"
    x-on:show-room-profile.window="showRoomProfile = true"
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

    {{-- Room Profile sidebar - Right --}}
    <section
            class="
                overflow-y-auto
                transition-all
                duration-300
                bg-white
                dark:bg-zinc-800
                border-l
                dark:border-zinc-700
                xl:max-w-lg
                flex-col
            "
            :class="showRoomProfile
                ? 'w-full max-w-full flex'
                : 'w-0'"
            x-cloak
        >
        @if ($this->showRoomProfile)
            <livewire:rooms.room-profile :$roomId lazy />
        @endif
    </section>
</div>
