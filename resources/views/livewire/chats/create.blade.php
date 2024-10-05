<div>
    <form wire:submit="store">
        <div class="mt-4">
            <x-input-label for="name" value="Name" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mt-4">
            <x-input-label for="members" value="Members" />
            <x-select-input wire:model="members" id="members" name="members" :options="$users" multiple />
            <x-input-error class="mt-2" :messages="$errors->get('members')" />
        </div>

        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Create') }}</x-primary-button>

            <x-action-message class="me-3" on="room-created">
                {{ __('Room created.') }}
            </x-action-message>
        </div>
    </form>
</div>
