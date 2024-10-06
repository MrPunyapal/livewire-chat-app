<div class="bg-white dark:bg-gray-800 w-3/4">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if ($room !== null)
                    <figure
                        class="rounded h-10 w-10 flex-shrink-0 transition-opacity group-hover:opacity-90 {{ $room->user->profile }}">
                        <img src="{{ $room->user->profile }}" alt="{{ $room->user->name }}" class="rounded h-10 w-10" />
                    </figure>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $room->name }}</p>
                @else
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        Please select room.
                    </p>
                @endif
            </div>
        </div>
        <div
            class="mt-4 h-[calc(100vh-210px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 flex flex-col-reverse">
            <div class="flex flex-col gap-4">
                @forelse ($chats as $chat)
                    @php
                        $isCurrentUser = $chat->user->id === auth()->user()->id;
                        // for temporary
                        $isCurrentUser = $loop->odd;
                    @endphp
                    <div @class([
                        'flex items-center space-x-2',
                        'justify-end' => $isCurrentUser,
                    ])>
                        @if (!$isCurrentUser)
                            <figure class="flex flex-shrink-0 self-start">
                                <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                                    class="h-8 w-8 object-cover rounded-full">
                            </figure>
                        @endif
                        <div class="flex items-center justify-center space-x-2">
                            <div class="block">
                                <div class="w-auto rounded-xl px-2 pb-2">
                                    <div @class([
                                        'font-medium text-gray-800 dark:text-gray-100',
                                        'text-right' => $isCurrentUser,
                                    ])>
                                        <small class="text-sm sm:text-md">
                                            {{ $chat->user->name }}
                                        </small>
                                    </div>
                                    <div @class([
                                        'text-xs sm:text-sm bg-slate-100 dark:bg-gray-700 dark:text-gray-400 p-2',
                                        'rounded-tl-3xl rounded-bl-3xl rounded-br-xl' => $isCurrentUser,
                                        'rounded-tr-3xl rounded-br-3xl rounded-bl-xl' => !$isCurrentUser,
                                    ])>
                                        <p>
                                            {{ $chat->message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($isCurrentUser)
                            <figure class="flex flex-shrink-0 self-start">
                                <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                                    class="h-8 w-8 object-cover rounded-full">
                            </figure>
                        @endif
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4">
                        <div class="flex items-center gap-3 px-4">
                            <p class="text-gray-500 dark:text-gray-400">No chats found</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="mt-4">
            <form wire:submit="sendMessage">
                <div class="flex items-center gap-3">
                    <input type="text" wire:model="message" class="w-full rounded-lg border-gray-300" />
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
