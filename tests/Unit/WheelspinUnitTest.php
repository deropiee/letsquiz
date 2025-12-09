<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WheelspinUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_gems_after_spinning_wheel()
    {
        $user = User::factory()->create(['gems' => 0]);
        $this->actingAs($user);

        // Simuleer een wheelspin waarbij 2000 gems worden gewonnen
        $spinAmount = 2000;
        $response = $this->postJson(route('gems.add'), ['amount' => $spinAmount]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'gems' => 2000,
                'added' => 2000,
                'previous' => 0
            ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'gems' => 2000
        ]);
    }

    public function test_authenticated_user_can_add_gems_via_wheelspin()
    {
        $user = User::factory()->create(['gems' => 0]);
        $this->actingAs($user);

        $response = $this->postJson(route('gems.add'), ['amount' => 500]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'gems' => 500,
                'added' => 500,
                'previous' => 0
            ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'gems' => 500
        ]);
    }

    public function test_cannot_add_invalid_amount_of_gems()
    {
        $user = User::factory()->create(['gems' => 0]);
        $this->actingAs($user);

        $response = $this->postJson(route('gems.add'), ['amount' => 0]);
        $response->assertStatus(422);

        $response = $this->postJson(route('gems.add'), ['amount' => 1000001]);
        $response->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_add_gems()
    {
        $response = $this->postJson(route('gems.add'), ['amount' => 500]);
        $response->assertStatus(401);
    }
}
