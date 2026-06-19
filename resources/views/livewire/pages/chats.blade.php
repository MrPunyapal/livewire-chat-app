<div
    class="relative flex h-dvh max-h-dvh min-h-0 overflow-hidden bg-white dark:bg-zinc-900"
    x-data="{ roomsOpen: false, showRoomProfile: false }"
    x-on:open-rooms.window="roomsOpen = true"
    x-on:close-rooms.window="roomsOpen = false"
    x-on:room-selected.window="roomsOpen = false"
    x-on:keydown.escape.window="roomsOpen = false; showRoomProfile = false"
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
    <section  class="
        overflow-y-auto
        transition-all
        duration-300
        bg-white
        dark:bg-zinc-800
        border-l
        border-zinc-700
        xl:max-w-lg
        flex-col
    "
    :class="showRoomProfile
        ? 'w-full max-w-full flex'
        : 'w-0'"
    x-cloak
    >
        <header class="flex items-center gap-4 px-6 py-5 border-b border-zinc-200 dark:border-zinc-700">
            <flux:button x-on:click="showRoomProfile = false" icon="x-mark" icon:variant="outline" variant="subtle" />
            <flux:heading variant="strong">Room info</flux:heading>
            <flux:button icon="pencil" class="ml-auto" icon:variant="outline" variant="subtle" />
        </header>
        <div class="p-4 dark:border-zinc-700 flex flex-col items-center space-y-2 h-full">
            <div class="flex flex-col items-center w-full lg:max-w-md mx-auto space-y-2">
                <div>
                    <img src="https://images.pexels.com/photos/34598816/pexels-photo-34598816.png"
                        class="w-28 h-28 rounded-full object-cover" alt="" />
                </div>
                <h1 class="text-lg md:text-2xl">Laragang</h1>
                <p class="text-sm md:text-base text-zinc-500 dark:text-zinc-400">About</p>
                <p class="text-sm md:text-base text-zinc-500 dark:text-zinc-200">Official chat account of laragang</p>
            </div>

            <flux:separator class="my-5"/>


            {{--show members list --}}
            <div class="w-full space-y-1">
                <div class="mb-2 flex items-center justify-between w-full">
                    <flux:text>11 members</flux:text>
                    <flux:button icon="magnifying-glass" icon:variant="outline" variant="subtle" />
                </div>

                <ul class="flex flex-col gap-5">
                    <li class="flex gap-4 items-center">
                        {{-- image --}}
                        <img class="size-12 rounded-full " src="https://ui-avatars.com/api/?name=parthil&color=7F9CF5&background=EBF4FF" alt="">

                        <div>
                            {{-- name --}}
                            <flux:text variant="strong" class="text-base">Parthil Patel</flux:text>
                            {{-- bio --}}
                            <flux:text>Good is not good when better is possible</flux:text>
                        </div>
                    </li>
                    <li class="flex gap-4 items-center">
                        {{-- image --}}
                        <img class="size-12 rounded-full " src="https://ui-avatars.com/api/?name=parthil&color=7F9CF5&background=EBF4FF" alt="">

                        <div>
                            {{-- name --}}
                            <flux:text variant="strong" class="text-base">Parthil Patel</flux:text>
                            {{-- bio --}}
                            <flux:text>Good is not good when better is possible</flux:text>
                        </div>
                    </li><li class="flex gap-4 items-center">
                        {{-- image --}}
                        <img class="size-12 rounded-full " src="https://ui-avatars.com/api/?name=parthil&color=7F9CF5&background=EBF4FF" alt="">

                        <div>
                            {{-- name --}}
                            <flux:text variant="strong" class="text-base">Parthil Patel</flux:text>
                            {{-- bio --}}
                            <flux:text>Good is not good when better is possible</flux:text>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>
