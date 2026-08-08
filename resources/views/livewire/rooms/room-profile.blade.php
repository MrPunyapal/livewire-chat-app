<div>
    <header class="flex items-center gap-4 px-6 py-5 border-b border-zinc-200 dark:border-zinc-700">
        <flux:button x-on:click="showRoomProfile = false" icon="x-mark" icon:variant="outline" variant="subtle" />
        <flux:heading variant="strong">Room info</flux:heading>
        <flux:button icon="pencil" class="ml-auto" icon:variant="outline" variant="subtle" />
    </header>
    <div class="p-4 dark:border-zinc-700 flex flex-col items-center space-y-2 h-full">
        <div class="flex flex-col items-center w-full lg:max-w-md mx-auto space-y-2">
            <div>
                <img src="{{ $room->user->profile }}"
                    class="w-28 h-28 rounded-full object-cover" alt="{{ $room->user->name }}" />
            </div>
            <h1 class="text-lg md:text-2xl">{{ $room->name }}</h1>
            <p class="text-sm md:text-base text-zinc-500 dark:text-zinc-400">About</p>
            <p class="text-sm md:text-base text-zinc-500 dark:text-zinc-200">{{ $room->description ?: '--' }}</p>
        </div>

        <flux:separator class="my-5" />


        {{-- show members list --}}
        <div class="w-full space-y-1">
            <div class="mb-2 flex items-center justify-between w-full">
                <flux:text>{{ $room->users->count() }} members</flux:text>
                <flux:button icon="magnifying-glass" icon:variant="outline" variant="subtle" />
            </div>
            <div class="w-full mb-3">
                <flux:modal.trigger name="add-members">
                    <flux:button icon="user-plus" variant="ghost" class="shrink-0 w-full justify-start! cursor-pointer">
                        {{ __('Add member') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
            <ul class="flex flex-col gap-5">
                @forelse($existingMembers as $member)
                    <li class="flex gap-4 items-center">
                        {{-- image --}}
                        <img class="size-12 rounded-full "
                            src="{{ $member->profile }}"
                            alt="{{ $member->name }}">

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
