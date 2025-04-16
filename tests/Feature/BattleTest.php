<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Pokemon;
use App\Models\Battle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BattleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    /**
     * Create a trainer with pokemons for testing.
     */
    private function createTrainerWithPokemons()
    {
        $trainer = Trainer::factory()->create();
        
        // Create pokemons for the trainer
        Pokemon::factory()->count(2)->create([
            'trainer_id' => $trainer->id,
            'stats' => ['hp' => 50, 'attack' => 60, 'defense' => 40],
            'level' => 20,
        ]);
        
        return $trainer;
    }

    /**
     * Test listing all battles.
     */
    public function test_user_can_list_all_battles(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create some battles
        Battle::factory()->count(3)->create();

        // Make request
        $response = $this->getJson('/api/battles');

        // Assert successful response with battles
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     '*' => ['id', 'trainer1_id', 'trainer2_id', 'winner_id', 'date', 'created_at', 'updated_at']
                 ]]);
    }

    /**
     * Test creating a new battle.
     */
    public function test_trainer_can_create_battle(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create trainers with pokemons
        $trainer1 = $this->createTrainerWithPokemons();
        $trainer2 = $this->createTrainerWithPokemons();

        // Define battle data
        $battleData = [
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        // Make request
        $response = $this->postJson('/api/battles', $battleData);

        // Assert successful response
        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => [
                     'id', 'trainer1_id', 'trainer2_id', 'winner_id', 'date'
                 ]]);

        // Assert battle exists in database
        $this->assertDatabaseHas('battles', [
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
        ]);

        // Assert winner's points were updated
        $winnerId = $response->json('data.winner_id');
        $winner = Trainer::find($winnerId);
        $this->assertGreaterThan(0, $winner->points);
    }

    /**
     * Test that a battle cannot be created if trainers are the same.
     */
    public function test_cannot_create_battle_with_same_trainer(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a trainer with pokemons
        $trainer = $this->createTrainerWithPokemons();

        // Define battle data with same trainer
        $battleData = [
            'trainer1_id' => $trainer->id,
            'trainer2_id' => $trainer->id,
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        // Make request
        $response = $this->postJson('/api/battles', $battleData);

        // Assert validation error
        $response->assertStatus(422)
                 ->assertJsonValidationErrors('trainer2_id');
    }

    /**
     * Test viewing a specific battle.
     */
    public function test_can_view_battle_details(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a battle
        $battle = Battle::factory()->create();

        // Make request
        $response = $this->getJson('/api/battles/' . $battle->id);

        // Assert successful response with battle details and trainers
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     'id', 'trainer1_id', 'trainer2_id', 'winner_id', 'date', 
                     'created_at', 'updated_at', 'trainer1', 'trainer2', 'winner'
                 ]]);
    }

    /**
     * Test deleting a battle.
     */
    public function test_admin_can_delete_battle(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create trainers and a battle
        $trainer1 = $this->createTrainerWithPokemons();
        $trainer2 = $this->createTrainerWithPokemons();
        
        $battle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'winner_id' => $trainer1->id,
        ]);
        
        // Update winner's points
        $trainer1->increment('points', 3);
        
        // Record the points before deletion
        $pointsBefore = $trainer1->fresh()->points;

        // Make request
        $response = $this->deleteJson('/api/battles/' . $battle->id);

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Battle deleted successfully']);

        // Assert battle was deleted from database
        $this->assertDatabaseMissing('battles', [
            'id' => $battle->id,
        ]);
        
        // Assert winner's points were reverted
        $pointsAfter = $trainer1->fresh()->points;
        $this->assertEquals($pointsBefore - 3, $pointsAfter);
    }
}