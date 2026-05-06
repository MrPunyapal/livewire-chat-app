<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee(route('chats'));
    }

    public function test_authenticated_users_can_visit_the_chats_route(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('chats'));

        $response->assertOk();
    }

    public function test_authenticated_users_are_redirected_to_profile_settings_from_profile_route(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile'));

        $response->assertRedirect(route('profile.edit'));
    }
}
