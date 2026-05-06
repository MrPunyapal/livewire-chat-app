<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id): bool {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chats.{roomId}', function (User $user, int $roomId): bool {
    return $user->rooms()
        ->where('rooms.id', $roomId)
        ->exists();
});
