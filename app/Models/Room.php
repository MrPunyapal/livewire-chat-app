<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Collection<array-key, User> $users
 * @property-read Collection<array-key, Chat> $chats
 */
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * Get the user that owns the room.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the members for the room.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'members');
    }

    /**
     * Get the chats for the room.
     *
     * @return HasMany<Chat, $this>
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function image(): Attribute
    {
        return Attribute::get(
            function (mixed $value): string {
                if (! is_string($value) || $value === '') {
                    return $this->user->profile;
                }

                return $value;
            }
        );
    }
}
