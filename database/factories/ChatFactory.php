<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Chat>
 */
class ChatFactory extends Factory
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
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'message' => $this->faker->sentence,
        ];
    }
}
