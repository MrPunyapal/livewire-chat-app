<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ChatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Room $room
 */
class Chat extends Model
{
    /** @use HasFactory<ChatFactory> */
    use HasFactory;

    /**
     * Get the user who sent the chat.
     *
     * @return BelongsTo<User, Chat>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room in which the chat was sent.
     *
     * @return BelongsTo<Room, Chat>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
