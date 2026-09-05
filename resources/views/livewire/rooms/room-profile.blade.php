<div>
    <header class="flex items-center gap-4 border-b border-zinc-200 px-6 py-5 dark:border-zinc-700">
        <flux:button x-on:click="showRoomProfile = false" icon="x-mark" icon:variant="outline" variant="subtle" />
        <flux:heading variant="strong">Room info</flux:heading>
    </header>
    <div class="flex h-full flex-col items-center space-y-2 p-4 dark:border-zinc-700">
        {{-- Current room image upload --}}
        <div class="mx-auto flex w-full flex-col items-center space-y-2 pt-2 lg:max-w-md">
            <div class="group relative">
                @if ($image && $image->isPreviewable())
                    <img
                        src="{{ $image->temporaryUrl() }}"
                        class="h-28 w-28 rounded-full object-cover ring-4 ring-white dark:ring-zinc-800"
                        alt="{{ $room->name }}"
                    />
                @else
                    <img
                        src="{{ $room->image }}"
                        class="h-28 w-28 rounded-full object-cover ring-4 ring-white dark:ring-zinc-800"
                        alt="{{ $room->image }}"
                    />
                @endif

                <label
                    for="room-image-upload"
                    class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-zinc-900/50 opacity-0 backdrop-blur-[1px] transition-opacity duration-200 group-hover:opacity-100 focus-within:opacity-100"
                    aria-label="{{ __('Change Room Image') }}"
                >
                    <span class="flex flex-col items-center gap-1 text-white">
                        <flux:icon.camera class="size-6" />
                        <span class="text-xs font-medium">{{ __('Change') }}</span>
                    </span>
                </label>

                <input
                    id="room-image-upload"
                    type="file"
                    wire:model.live="image"
                    accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                    class="hidden"
                />

                <div
                    wire:loading
                    wire:target="image"
                    class="absolute inset-0 flex items-center justify-center rounded-full bg-white/80 dark:bg-zinc-900/80"
                >
                    <flux:icon.loading class="size-6 animate-spin text-zinc-600 dark:text-zinc-300" />
                </div>
            </div>

            @if ($errors->has('image'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => (show = false), 3000)"
                    x-show="show"
                    x-transition.opacity.duration.300ms
                >
                    <flux:error name="image" class="text-sm" />
                </div>
            @endif
            {{-- <flux:error name="image" class="text-sm"/> --}}

            <div wire:loading wire:target="image" class="text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('Uploading...') }}
            </div>
            <h1 class="text-lg md:text-2xl">{{ $room->name }}</h1>
            <p class="text-sm text-zinc-500 md:text-base dark:text-zinc-400">About</p>
            <p class="text-sm text-zinc-500 md:text-base dark:text-zinc-200">{{ $room->description ?: '--' }}</p>
        </div>

        <flux:separator class="my-5" />

        {{-- show members list --}}
        <div class="w-full space-y-1">
            <div class="mb-2 flex w-full items-center justify-between">
                <flux:text>{{ $room->users->count() }} members</flux:text>
                <flux:button icon="magnifying-glass" icon:variant="outline" variant="subtle" />
            </div>
            <div class="mb-3 w-full">
                <flux:modal.trigger name="add-members">
                    <flux:button icon="user-plus" variant="ghost" class="w-full shrink-0 cursor-pointer justify-start!">
                        {{ __('Add member') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
            <ul class="flex flex-col gap-5">
                @forelse ($existingMembers as $member)
                    <li class="flex items-center gap-4">
                        {{-- image --}}
                        <img class="size-12 rounded-full" src="{{ $member->profile }}" alt="{{ $member->name }}" />

                        <div>
                            {{-- name --}}
                            <flux:text variant="strong" class="text-base">{{ $member->name }}</flux:text>
                            {{-- bio @TODO add bio field in user profile --}}
                            <flux:text>{{ $member?->bio ?: '--' }}</flux:text>
                        </div>
                    </li>
                @empty
                    <div>
                        <flux:text>No members exists</flux:text>
                    </div>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- add members modal --}}
    <flux:modal
        name="add-members"
        class="w-full max-w-lg"
        x-on:members-added.window="$dispatch('modal-close', { name: 'add-members' })"
    >
        <div class="space-y-6">
            <div class="flex items-start gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-300">
                    <flux:icon.user-plus class="size-5" />
                </div>

                <div class="min-w-0">
                    <flux:heading size="lg">{{ __('Add Members') }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                        {{ __('Add new members to :room.', ['room' => $room->name]) }}
                    </flux:text>
                </div>
            </div>

            <livewire:rooms.add-members :$existingMembers :$room />
        </div>
    </flux:modal>
</div>
