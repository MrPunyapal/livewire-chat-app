<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main @class(['!p-0 overflow-hidden' => request()->routeIs('chats')])> {{ $slot }} </flux:main>
</x-layouts::app.sidebar>
