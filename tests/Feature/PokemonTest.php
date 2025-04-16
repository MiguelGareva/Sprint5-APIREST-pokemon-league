<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PokemonTest extends TestCase
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
     * Test listing all pokemons.
     */
    public function test_user_can_list_all_pokemons(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create some pokemons
        Pokemon::factory()->count(3)->create();

        // Make request
        $response = $this->getJson('/api/pokemons');

        // Assert successful response with pokemons
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     '*' => ['id', 'name', 'type', 'level', 'stats', 'trainer_id', 'created_at', 'updated_at']
                 ]]);
    }

    /**
     * Test filtering pokemons by type.
     */
    public function test_can_filter_pokemons_by_type(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create pokemons with different types
        Pokemon::factory()->create(['type' => 'Fire']);
        Pokemon::factory()->create(['type' => 'Water']);
        Pokemon::factory()->create(['type' => 'Fire']);

        // Make request with filter
        $response = $this->getJson('/api/pokemons?type=Fire');

        // Assert successful response with filtered pokemons
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
        
        // Verify all returned pokemons are of type Fire
        $data = $response->json('data');
        foreach ($data as $pokemon) {
            $this->assertEquals('Fire', $pokemon['type']);
        }
    }

    /**
     * Test listing available pokemons (without trainer).
     */
    public function test_can_list_available_pokemons(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create some pokemons with trainers
        Pokemon::factory()->count(2)->create(['trainer_id' => Trainer::factory()->create()->id]);
        
        // Create some pokemons without trainers
        Pokemon::factory()->count(3)->create(['trainer_id' => null]);

        // Make request
        $response = $this->getJson('/api/pokemons/available');

        // Assert successful response with available pokemons
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
        
        // Verify all returned pokemons have null trainer_id
        $data = $response->json('data');
        foreach ($data as $pokemon) {
            $this->assertNull($pokemon['trainer_id']);
        }
    }

    /**
     * Test creating a new pokemon.
     */
    public function test_admin_can_create_pokemon(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Define pokemon data
        $pokemonData = [
            'name' => 'Pikachu',
            'type' => 'Electric',
            'level' => 25,
            'stats' => ['hp' => 70, 'attack' => 80, 'defense' => 50],
            'trainer_id' => null,
        ];

        // Make request
        $response = $this->postJson('/api/pokemons', $pokemonData);

        // Assert successful response
        $response->assertStatus(201)
                 ->assertJson(['data' => [
                     'name' => 'Pikachu',
                     'type' => 'Electric',
                     'level' => 25,
                 ]]);

        // Assert pokemon exists in database
        $this->assertDatabaseHas('pokemons', [
            'name' => 'Pikachu',
            'type' => 'Electric',
            'level' => 25,
        ]);
    }

    /**
     * Test viewing a specific pokemon.
     */
    public function test_can_view_pokemon_details(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a pokemon
        $pokemon = Pokemon::factory()->create();

        // Make request
        $response = $this->getJson('/api/pokemons/' . $pokemon->id);

        // Assert successful response with pokemon details
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     'id', 'name', 'type', 'level', 'stats', 'trainer_id', 'created_at', 'updated_at', 'trainer'
                 ]]);
    }

    /**
     * Test updating a pokemon.
     */
    public function test_admin_can_update_pokemon(): void
    {
        // Create admin user with appropriate role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        // Create a pokemon
        $pokemon = Pokemon::factory()->create(['level' => 5]);

        // Define update data
        $updateData = [
            'level' => 10,
        ];

        // Make request
        $response = $this->putJson('/api/pokemons/' . $pokemon->id, $updateData);

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['data' => [
                     'id' => $pokemon->id,
                     'level' => 10,
                 ]]);

        // Assert pokemon was updated in database
        $this->assertDatabaseHas('pokemons', [
            'id' => $pokemon->id,
            'level' => 10,
        ]);
    }

    /**
     * Test assigning a pokemon to a trainer.
     */
    public function test_can_assign_pokemon_to_trainer(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a pokemon without trainer
        $pokemon = Pokemon::factory()->create(['trainer_id' => null]);
        
        // Create a trainer
        $trainer = Trainer::factory()->create();

        // Make request
        $response = $this->postJson("/api/pokemons/{$pokemon->id}/trainers/{$trainer->id}");

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Pokemon assigned to trainer successfully']);

        // Assert pokemon was assigned to trainer in database
        $this->assertDatabaseHas('pokemons', [
            'id' => $pokemon->id,
            'trainer_id' => $trainer->id,
        ]);
    }

    /**
     * Test releasing a pokemon from a trainer.
     */
    public function test_can_release_pokemon_from_trainer(): void
    {
        // Create user with appropriate permission
        $user = User::factory()->create();
        $user->assignRole('trainer');
        Passport::actingAs($user);

        // Create a trainer
        $trainer = Trainer::factory()->create();
        
        // Create a pokemon with that trainer
        $pokemon = Pokemon::factory()->create(['trainer_id' => $trainer->id]);

        // Make request
        $response = $this->deleteJson("/api/pokemons/{$pokemon->id}/trainers/{$trainer->id}");

        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Pokemon released successfully']);

        // Assert pokemon was released from trainer in database
        $this->assertDatabaseHas('pokemons', [
            'id' => $pokemon->id,
            'trainer_id' => null,
        ]);
    }
}