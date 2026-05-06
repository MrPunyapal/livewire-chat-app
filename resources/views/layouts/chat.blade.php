<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="!p-0 overflow-hidden">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
