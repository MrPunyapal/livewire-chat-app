<div>
    <form
        wire:submit="store"
        class="space-y-6"
    >
        <div>
            <x-input-label
                for="name"
                value="Room Name"
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
            />
            <x-text-input
                wire:model="name"
                id="name"
                name="name"
                type="text"
                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter room name..."
            />
            <x-input-error
                class="mt-2"
                :messages="$errors->get('name')"
            />
        </div>

        <div>
            <x-input-label
                for="members"
                value="Invite Members"
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
            />
            <x-select-input
                wire:model="members"
                id="members"
                name="members"
                :options="$users"
                multiple
                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
            />
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Select users to invite to this room</p>
            <x-input-error
                class="mt-2"
                :messages="$errors->get('members')"
            />
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <x-action-message
                class="text-green-600 dark:text-green-400"
                on="room-created"
            >
                {{ __('Room created successfully!') }}
            </x-action-message>
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 px-6 py-2.5">
                {{ __('Create Room') }}
            </x-primary-button>
        </div>
    </form>
</div>
