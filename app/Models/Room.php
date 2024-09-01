<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property-read User $user
 */
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * Get the user that owns the room.
     *
     * @return BelongsTo<User, Room>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
