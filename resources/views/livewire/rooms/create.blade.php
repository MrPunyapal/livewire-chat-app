<div class="overflow-y-auto max-h-[600px]">
    <form wire:submit="store" class="space-y-6">
        <flux:field>
            <flux:label>{{ __('Room Name') }}</flux:label>
            <flux:input
                wire:model="name"
                id="name"
                name="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter room name..."
            />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Invite Members') }}</flux:label>
            <x-multi-select-input
                id="members"
                name="members"
                :options="$users"
                placeholder="Select members"
                wire-model="members"
            />
            <flux:error name="members" />
        </flux:field>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
            <flux:button variant="primary" type="submit">
                {{ __('Create Room') }}
            </flux:button>
        </div>
    </form>
</div>
