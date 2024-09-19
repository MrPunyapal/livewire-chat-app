<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read User[] $members
 * @property-read Chat[] $chats
 */
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Get the user that owns the room.
     *
     * @return BelongsTo<User, Room>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the members for the room.
     *
     * @return BelongsToMany<User>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'members');
    }

    /**
     * Get the chats for the room.
     *
     * @return HasMany<Chat>
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
