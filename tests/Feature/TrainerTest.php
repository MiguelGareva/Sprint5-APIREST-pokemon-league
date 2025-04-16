<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    /**
     * Test listing all trainers.
     */
    public function test_admin_can_list_all_trainers(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create some trainers
        Trainer::factory()->count(3)->create();

        // Make request
        $response = $this->getJson('/api/trainers');

        // Assert successful response with trainers
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     '*' => ['id', 'name', 'points', 'user_id', 'created_at', 'updated_at']
                 ]]);
    }

    /**
     * Test that unauthorized users cannot list trainers.
     */
    public function test_unauthorized_users_cannot_list_trainers(): void
    {
        // Create user without appropriate role
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Make request
        $response = $this->getJson('/api/trainers');

        // Assert forbidden response
        $response->assertStatus(403);
    }

    /**
     * Test creating a new trainer.
     */
    public function test_admin_can_create_trainer(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create user for the trainer
        $user = User::factory()->create();

        // Define trainer data
        $trainerData = [
            'name' => 'Ash Ketchum',
            'user_id' => $user->id,
        ];

        // Make request
        $response = $this->postJson('/api/trainers', $trainerData);

        // Assert successful response
        $response->assertStatus(201)
                 ->assertJson(['data' => [
                     'name' => 'Ash Ketchum',
                     'user_id' => $user->id,
                     'points' => 0,
                 ]]);

        // Assert trainer exists in database
        $this->assertDatabaseHas('trainers', [
            'name' => 'Ash Ketchum',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test viewing a specific trainer.
     */
    public function test_can_view_trainer_details(): void
    {
        // Create user with trainer role
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a trainer
        $trainer = Trainer::factory()->create();

        // Make request
        $response = $this->getJson('/api/trainers/' . $trainer->id);

        // Assert successful response with trainer details
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     'id', 'name', 'points', 'user_id', 'created_at', 'updated_at', 'pokemons'
                 ]]);
    }

    /**
     * Test updating a trainer.
     */
    public function test_admin_can_update_trainer(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create a trainer
        $trainer = Trainer::factory()->create();

        // Define update data
        $updateData = [
            'name' => 'Gary Oak',
        ];

        // Make request
        $response = $this->putJson('/api/trainers/' . $trainer->id, $updateData);

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['data' => [
                     'id' => $trainer->id,
                     'name' => 'Gary Oak',
                 ]]);

        // Assert trainer was updated in database
        $this->assertDatabaseHas('trainers', [
            'id' => $trainer->id,
            'name' => 'Gary Oak',
        ]);
    }

    /**
     * Test deleting a trainer.
     */
    public function test_admin_can_delete_trainer(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create a trainer
        $trainer = Trainer::factory()->create();

        // Make request
        $response = $this->deleteJson('/api/trainers/' . $trainer->id);

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Trainer deleted successfully']);

        // Assert trainer was deleted from database
        $this->assertDatabaseMissing('trainers', [
            'id' => $trainer->id,
        ]);
    }

    /**
     * Test the ranking endpoint.
     */
    public function test_guest_can_view_ranking(): void
    {
        // Create several trainers with different points
        Trainer::factory()->create(['points' => 100]);
        Trainer::factory()->create(['points' => 250]);
        Trainer::factory()->create(['points' => 50]);

        // Make request without authentication
        $response = $this->getJson('/api/trainers/ranking');

        // Assert successful response with ordered trainers
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     '*' => ['id', 'name', 'points']
                 ]]);

        // Assert trainers are ordered by points in descending order
        $data = $response->json('data');
        $this->assertTrue($data[0]['points'] >= $data[1]['points']);
        $this->assertTrue($data[1]['points'] >= $data[2]['points']);
    }
}