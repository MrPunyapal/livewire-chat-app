<div>
    <form wire:submit="submit" class="space-y-5">
        <flux:field>
            <flux:label>{{ __('Select Members') }}</flux:label>
            <x-multi-select-input
                id="members"
                name="members"
                :options="$users"
                :selected="$members"
                placeholder="Select members"
                wire-model="members"
            />
            <flux:error name="members" />
        </flux:field>

        <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700 sm:flex-row sm:justify-end">
            <flux:modal.close>
                <flux:button type="button" variant="filled" class="w-full sm:w-auto">
                    {{ __('Cancel') }}
                </flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit" class="w-full sm:w-auto">
                {{ __('Add member') }}
            </flux:button>
        </div>
    </form>
</div>
