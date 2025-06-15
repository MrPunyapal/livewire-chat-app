<div @class([
    'flex items-start mb-4 animate-fade-in group relative gap-2',
    'flex-row-reverse' => $isCurrentUser,
])>
    <figure class="flex shrink-0">
        <img
            src="{{ $chat->user->profile }}"
            alt="{{ $chat->user->name }}"
            @class([
                'w-10 h-10 object-cover rounded-full ring-2 ring-offset-2',
                'ring-blue-500/30 ring-offset-white dark:ring-offset-gray-900' => $isCurrentUser,
                'ring-gray-300/50 ring-offset-white dark:ring-offset-gray-900' => !$isCurrentUser,
            ])
        >
    </figure>

    <div @class([
        'flex flex-col max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg relative',
        'items-end' => $isCurrentUser,
    ])>
        <div @class([
            'flex items-center mb-1 gap-2',
            'flex-row-reverse justify-end' => $isCurrentUser,
        ])>
            <div @class([
                'flex items-center gap-1.5',
                'flex-row-reverse' => $isCurrentUser,
            ])>
                <span class="font-medium text-sm text-gray-700 dark:text-gray-300">
                    {{ $chat->user->name }}
                </span>
                @if ($chat->updated_at > $chat->created_at && is_null($chat->deleted_at))
                    <span class="text-xs text-gray-500 dark:text-gray-400 italic">(edited)</span>
                @endif
            </div>

            <!-- Action buttons -->
            <div @class([
                'flex gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 z-20',
                'bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-lg px-2 py-1 shadow-lg border border-gray-200/80 dark:border-gray-600/80',
                'ml-2' => !$isCurrentUser,
                'mr-2' => $isCurrentUser,
            ])>
                @if (is_null($chat->deleted_at))
                    @if ($isCurrentUser)
                        <button
                            wire:click="edit"
                            class="p-1.5 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors duration-150"
                            title="Edit message"
                        >
                            <x-icons.edit class="h-3.5 w-3.5" />
                        </button>
                        <button
                            x-on:click="$dispatch('open-modal', 'confirm-chat-deletion-{{ $chat->id }}')"
                            class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-colors duration-150"
                            title="Delete message"
                        >
                            <x-icons.trash class="h-3.5 w-3.5" />
                        </button>
                    @endif

                    <button
                        wire:click="reply"
                        class="p-1.5 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors duration-150"
                        title="Reply to message"
                    >
                        <x-icons.reply class="h-3.5 w-3.5" />
                    </button>

                    <button
                        wire:click="toggleFavourite"
                        @class([
                            'p-1.5 rounded-md transition-colors duration-150',
                            'text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 dark:hover:text-yellow-400' => $chat->favouritedBy->doesntContain(
                                auth()->id()),
                            'text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-400' => $chat->favouritedBy->contains(
                                auth()->id()),
                        ])
                        title="{{ $chat->favouritedBy->contains(auth()->id()) ? 'Remove from favourite chats' : 'Mark as favourite' }}"
                    >
                        <x-icons.star @class([
                            'h-3.5 w-3.5',
                            'fill-current' => $chat->favouritedBy->contains(auth()->id()),
                        ]) />
                    </button>
                @endif
            </div>
        </div>

        <div @class([
            'relative group/bubble flex',
            'justify-end' => $isCurrentUser,
        ])>
            <div @class([
                'relative px-4 py-2 rounded-2xl shadow-sm max-w-full word-wrap break-words',
                'bg-blue-600 text-white chat-bubble-user' => $isCurrentUser,
                'bg-white dark:bg-gray-700 dark:text-gray-100 text-gray-900 border border-gray-200 dark:border-gray-600 chat-bubble-other' => !$isCurrentUser,
            ])>
                @if ($chat->deleted_at === null)
                    @if ($chat->parent)
                        <div @class([
                            'mb-2 p-2 rounded-lg text-xs border-l-2 bg-black/5 dark:bg-white/5',
                            'border-blue-300' => $isCurrentUser,
                            'border-gray-300 dark:border-gray-500' => !$isCurrentUser,
                        ])>
                            <p @class([
                                'flex items-center gap-2 text-sm font-medium',
                                'text-blue-100' => $isCurrentUser,
                                'text-gray-600 dark:text-gray-300' => !$isCurrentUser,
                            ])>
                                <x-icons.reply class="h-3 w-3 flex-shrink-0" />
                                @if ($chat->parent->deleted_at === null)
                                    <span class="truncate">{{ Str::limit($chat->parent->message, 80) }}</span>
                                @else
                                    <span class="text-red-400 dark:text-red-500 italic">
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
                    <p class="text-sm leading-relaxed text-red-500 dark:text-red-400 italic flex items-center gap-2">
                        <x-icons.trash class="h-3 w-3 flex-shrink-0" />
                        This message has been deleted.
                    </p>
                @endif
            </div>
        </div>

        <div @class([
            'text-xs text-gray-500 dark:text-gray-400 mt-1.5 flex items-center gap-1',
            'justify-end' => $isCurrentUser,
        ])>
            <span>{{ $chat->updated_at->diffForHumans() }}</span>
            @if ($chat->favouritedBy->contains(auth()->id()))
                <x-icons.star class="h-3 w-3 text-yellow-500 fill-current" />
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if (is_null($chat->deleted_at) && $isCurrentUser)
        <x-modal
            name="confirm-chat-deletion-{{ $chat->id }}"
            maxWidth="md"
            focusable
        >
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg
                            class="w-6 h-6 text-red-600 dark:text-red-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Delete Message
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            This action cannot be undone.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        "{{ Str::limit($chat->message, 100) }}"
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <x-secondary-button
                        x-on:click="$dispatch('close-modal', 'confirm-chat-deletion-{{ $chat->id }}')"
                        class="px-6 py-2.5"
                    >
                        Cancel
                    </x-secondary-button>
                    <x-danger-button
                        wire:click="delete"
                        x-on:click="$dispatch('close-modal', 'confirm-chat-deletion-{{ $chat->id }}')"
                        class="px-6 py-2.5"
                    >
                        Delete Message
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
