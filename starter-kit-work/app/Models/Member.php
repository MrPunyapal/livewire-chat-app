<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $room_id
 * @property int $user_id
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Room $room
 * @property-read User $user
 */
class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    /**
     * Get the room that the member belongs to.
     *
     * @return BelongsTo<Room, $this>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user that the member belongs to.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
