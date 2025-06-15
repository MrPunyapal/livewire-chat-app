<div @class([
    'flex items-start mb-4 animate-fade-in group relative',
    'flex-row-reverse' => $isCurrentUser,
])>
    <figure class="flex shrink-0 self-start mx-3">
        <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
            class="h-10 w-10 object-cover rounded-full ring-2 ring-offset-2 ring-opacity-50 {{ $isCurrentUser ? 'ring-blue-400' : 'ring-gray-300' }}">
    </figure>

    <div class="flex flex-col max-w-md relative">
        <div @class(['flex items-center mb-1.5', 'flex-row-reverse justify-end' => $isCurrentUser, 'justify-start' => !$isCurrentUser])>
            <div @class(['flex items-center', 'flex-row-reverse' => $isCurrentUser])>
                <small class="font-medium text-xs text-gray-600 dark:text-gray-300">
                    {{ $chat->user->name }}
                </small>
                @if ($chat->updated_at > $chat->created_at && is_null($chat->deleted_at))
                    <span class="text-xs text-gray-400 dark:text-gray-500 mx-1.5 italic">(edited)</span>
                @endif
            </div>

            <!-- Action buttons positioned beside the name -->
            <div @class([
                'flex gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 z-10 flex-row bg-white dark:bg-gray-800 rounded-full px-2 py-1 shadow-md',
                'ml-2' => !$isCurrentUser,
                'mr-2' => $isCurrentUser,
            ])>
                @if (is_null($chat->deleted_at))
                    @if ($isCurrentUser)
                        <button wire:click="edit"
                            class="p-1 rounded-full text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors"
                            title="Edit message">
                            <x-icons.edit class="h-2.5 w-2.5" />
                        </button>
                        <button x-on:click="$dispatch('open-modal', 'confirm-chat-deletion-{{ $chat->id }}')"
                            class="p-1 rounded-full text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-colors"
                            title="Delete message">
                            <x-icons.trash class="h-2.5 w-2.5" />
                        </button>
                    @endif

                    <button wire:click="reply"
                        class="p-1 rounded-full text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors"
                        title="Reply to message">
                        <x-icons.reply class="h-2.5 w-2.5" />
                    </button>

                    <button wire:click="toggleFavourite" @class([
                        'p-1 rounded-full text-gray-500 transition-colors',
                        'hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400' => $chat->favouritedBy->doesntContain(
                            auth()->id()),
                        'text-blue-500 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400' => $chat->favouritedBy->contains(
                            auth()->id()),
                    ])
                        title="{{ $chat->favouritedBy->contains(auth()->id()) ? 'Remove from favourite chats' : 'Mark as favourite' }}">
                        <x-icons.star @class([
                            'h-2.5 w-2.5',
                            'text-blue-500' => $chat->favouritedBy->contains(auth()->id()),
                        ]) />
                    </button>
                @endif
            </div>
        </div>

        <div @class(['relative rounded-lg', 'text-right' => $isCurrentUser])>
            <div @class([
                'inline-block p-3.5 rounded-xl shadow-md relative',
                'bg-blue-500 text-white rounded-tr-none' => $isCurrentUser,
                'bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-tl-none' => !$isCurrentUser,
            ])>
                @if ($chat->deleted_at === null)
                    @if ($chat->parent)
                        <div
                            class="mb-3 p-2.5 border-l-3 rounded-md text-xs bg-opacity-25 dark:bg-opacity-25 border-gray-400 bg-gray-200 dark:bg-gray-600 dark:border-gray-500">
                            <p class="truncate text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <x-icons.reply class="text-gray-400 dark:text-gray-500 h-3.5 w-3.5" />
                                @if ($chat->parent->deleted_at === null)
                                    {{ Str::limit($chat->parent->message, 100) }}
                                @else
                                    <span class="text-red-400 dark:text-red-500">
                                        This message has been deleted.
                                    </span>
                                @endif
                            </p>
                        </div>
                    @endif
                    <p class="text-sm leading-relaxed">
                        {{ $chat->message }}
                    </p>
                @else
                    <p class="text-sm leading-relaxed text-red-400 dark:text-red-500">
                        This message has been deleted.
                    </p>
                @endif
            </div>
        </div>

        <div @class([
            'text-xs text-gray-400 mt-1.5',
            'text-right' => $isCurrentUser,
        ])>
            {{ $chat->updated_at->diffForHumans() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if (is_null($chat->deleted_at) && $isCurrentUser)
        <x-modal name="confirm-chat-deletion-{{ $chat->id }}" maxWidth="md" focusable>
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Delete Message
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to delete this message? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-chat-deletion-{{ $chat->id }}')">
                        Cancel
                    </x-secondary-button>
                    <x-danger-button wire:click="delete" x-on:click="$dispatch('close-modal', 'confirm-chat-deletion-{{ $chat->id }}')">
                        Delete
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
