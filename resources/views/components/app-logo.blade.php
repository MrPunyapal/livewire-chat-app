@props([
    'sidebar' => false,
])

@if ($sidebar)
    <flux:sidebar.brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot
            name="logo"
            class="bg-accent-content text-accent-foreground flex aspect-square size-8 items-center justify-center rounded-md"
        >
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot
            name="logo"
            class="bg-accent-content text-accent-foreground flex aspect-square size-8 items-center justify-center rounded-md"
        >
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
