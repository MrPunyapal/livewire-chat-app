<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ChatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int $user_id
 * @property int $room_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Chat|null $parent
 * @property-read User $user
 * @property-read Room $room
 * @property-read User[] $favoriteUsers
 */
#[Fillable(['parent_id', 'user_id', 'room_id', 'message'])]
class Chat extends Model
{
    /** @use HasFactory<ChatFactory> */
    use HasFactory;

    /**
     * Get the user who sent the chat.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent chat if this is a reply.
     *
     * @return BelongsTo<Chat, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'parent_id');
    }

    /**
     * Get the room in which the chat was sent.
     *
     * @return BelongsTo<Room, $this>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the users who marked this chat as a Favorite chat.
     *
     * @return BelongsToMany<User, $this>
     */
    public function favoriteUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user_favorite', 'chat_id', 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }
}
