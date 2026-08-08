<div>
    <form wire:submit="submit" class="space-y-4">
        <div class="flex items-center gap-3">
            <div
                class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-300">
                <flux:icon.user-plus class="size-5" />
            </div>

            <div class="min-w-0">
                <flux:heading size="lg">{{ __('Add member') }}</flux:heading>
            </div>
        </div>

        <flux:separator />

        <flux:text>All Users</flux:text>

        <flux:error name="members" />

        <flux:checkbox.group wire:model="members" variant="cards" class="flex-col overflow-y-auto h-75">
            @foreach ($allUsers as $user)
                @php
                    $isMember = $existingMembers->contains($user);
                @endphp
                <flux:checkbox class="flex gap-6 items-center justify-normal cursor-pointer p-2!" :value="$user->id"
                    :disabled="$isMember">
                    <flux:checkbox.indicator />
                    <div class="flex gap-2 items-center">
                        {{-- image --}}
                        <img class="size-10 rounded-full "
                            src="https://ui-avatars.com/api/?name={{ $user->name }}&color=7F9CF5&background=EBF4FF"
                            alt="{{ $user->name }} avatar">

                        <div>
                            {{-- name --}}
                            <flux:text variant="strong" class="text-base">{{ $user->name }}</flux:text>
                            <flux:text wire:show="{{ $isMember }}" variant="subtle" class="text-sm">Already
                                added to the group</flux:text>
                        </div>

                    </div>
                </flux:checkbox>
            @endforeach
        </flux:checkbox.group>


        <flux:button
            type="submit"
            variant="primary"
            class="w-full rounded-full">
            {{ __('Add member') }}
        </flux:button>
    </form>
</div>
