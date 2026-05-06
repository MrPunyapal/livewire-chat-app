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
                'ring-blue-500/30 ring-offset-white dark:ring-offset-zinc-900' => $isCurrentUser,
                'ring-zinc-300/50 ring-offset-white dark:ring-offset-zinc-900' => !$isCurrentUser,
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
                <span class="font-medium text-sm text-zinc-700 dark:text-zinc-300">
                    {{ $chat->user->name }}
                </span>
                @if ($chat->updated_at > $chat->created_at && is_null($chat->deleted_at))
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 italic">(edited)</span>
                @endif
            </div>

            <!-- Action buttons -->
            <div @class([
                'flex gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 z-20',
                'bg-white/95 dark:bg-zinc-800/95 backdrop-blur-sm rounded-lg px-2 py-1 shadow-lg border border-zinc-200/80 dark:border-zinc-600/80',
                'ml-2' => !$isCurrentUser,
                'mr-2' => $isCurrentUser,
            ])>
                @if (is_null($chat->deleted_at))
                    @if ($isCurrentUser)
                        <button
                            wire:click="edit"
                            class="p-1.5 rounded-md text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors duration-150"
                            title="Edit message"
                        >
                            <flux:icon.pencil-square class="h-3.5 w-3.5" />
                        </button>
                        <button
                            x-on:click="$dispatch('modal-show', { name: 'confirm-chat-deletion-{{ $chat->id }}' })"
                            class="p-1.5 rounded-md text-zinc-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-colors duration-150"
                            title="Delete message"
                        >
                            <flux:icon.trash class="h-3.5 w-3.5" />
                        </button>
                    @endif

                    <button
                        wire:click="reply"
                        class="p-1.5 rounded-md text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-colors duration-150"
                        title="Reply to message"
                    >
                        <flux:icon.arrow-uturn-left class="h-3.5 w-3.5" />
                    </button>

                    <button
                        wire:click="toggleFavorite"
                        @class([
                            'p-1.5 rounded-md transition-colors duration-150',
                            'text-zinc-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 dark:hover:text-yellow-400' => $chat->favoriteUsers->doesntContain(
                                auth()->id()),
                            'text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-400' => $chat->favoriteUsers->contains(
                                auth()->id()),
                        ])
                        title="{{ $chat->favoriteUsers->contains(auth()->id()) ? 'Remove from Favorite chats' : 'Mark as Favorite' }}"
                    >
                        <flux:icon.star @class([
                            'h-3.5 w-3.5',
                            'fill-current' => $chat->favoriteUsers->contains(auth()->id()),
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
                'bg-white dark:bg-zinc-700 dark:text-zinc-100 text-zinc-900 border border-zinc-200 dark:border-zinc-600 chat-bubble-other' => !$isCurrentUser,
            ])>
                @if ($chat->deleted_at === null)
                    @if ($chat->parent)
                        <div @class([
                            'mb-2 p-2 rounded-lg text-xs border-l-2 bg-black/5 dark:bg-white/5',
                            'border-blue-300' => $isCurrentUser,
                            'border-zinc-300 dark:border-zinc-500' => !$isCurrentUser,
                        ])>
                            <p @class([
                                'flex items-center gap-2 text-sm font-medium',
                                'text-blue-100' => $isCurrentUser,
                                'text-zinc-600 dark:text-zinc-300' => !$isCurrentUser,
                            ])>
                                <flux:icon.arrow-uturn-left class="h-3 w-3 flex-shrink-0" />
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
                        <flux:icon.trash class="h-3 w-3 flex-shrink-0" />
                        This message has been deleted.
                    </p>
                @endif
            </div>
        </div>

        <div @class([
            'text-xs text-zinc-500 dark:text-zinc-400 mt-1.5 flex items-center gap-1',
            'justify-end' => $isCurrentUser,
        ])>
            <span>{{ $chat->updated_at->diffForHumans() }}</span>
            @if ($chat->favoriteUsers->contains(auth()->id()))
                <flux:icon.star class="h-3 w-3 text-yellow-500 fill-current" />
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if (is_null($chat->deleted_at) && $isCurrentUser)
        <flux:modal name="confirm-chat-deletion-{{ $chat->id }}" class="max-w-md">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete Message') }}</flux:heading>
                    <flux:text class="mt-2">
                        {{ __('Are you sure? This action cannot be undone.') }}
                    </flux:text>
                </div>

                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 p-4">
                    <flux:text>
                        &ldquo;{{ Str::limit($chat->message, 100) }}&rdquo;
                    </flux:text>
                </div>

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button>{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="danger" wire:click="delete" x-on:click="$dispatch('modal-close', { name: 'confirm-chat-deletion-{{ $chat->id }}' })">
                        {{ __('Delete Message') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
