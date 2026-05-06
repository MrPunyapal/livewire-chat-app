<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
        ];
    }
}
