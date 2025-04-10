<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pokemon;
use Database\Seeders\PokemonSeeder;

class PokemonSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_pokemon_are_created(){

        // Run the seeder
        $this->seed(PokemonSeeder::class);

        // Check if Pokemons are created
        $this->assertDatabaseHas('pokemons', ['name' => 'Charizard']);
        $this->assertDatabaseHas('pokemons', ['name' => 'Blastoise']);
        $this->assertDatabaseHas('pokemons', ['name' => 'Venusaur']);

        //Check if the total count is correct (should be 60+ Pokemons)
        $this->assertGreaterThan(60, Pokemon::count());
    }

    public function test_pokemon_types_are_correct(){
        // Run the seeder
        $this->seed(PokemonSeeder::class);

        // Check if types are correctly assigned
        $this->assertDatabaseHas('pokemons', ['name' => 'Charizard', 'type' => 'Fire']);
        $this->assertDatabaseHas('pokemons', ['name' => 'Blastoise', 'type' => 'Water']);
        $this->assertDatabaseHas('pokemons', ['name' => 'Jolteon', 'type' => 'Electric']);
        $this->assertDatabaseHas('pokemons', ['name' => 'Alakazam', 'type' => 'Psychic']);

        // Count hoy many of each type we have to ensure a good distribution
        $fireCount = Pokemon::where('type', 'Fire')->count();
        $waterCount = Pokemon::where('type', 'Water')->count();
        $grassCount = Pokemon::where('type', 'Grass')->count();

        // Ensure we have a reasonable number of each common type
        $this->assertGreaterThan(5, $fireCount);
        $this->assertGreaterThan(5, $waterCount);
        $this->assertGreaterThan(5, $grassCount);
    }

    public function test_pokemon_stats_are_stored_correctly(){
        // Run the seeder
        $this->seed(PokemonSeeder::class);

        // Get a sample Pokemon
        $pokemon = Pokemon::where('name', 'Charizard')->first();

        // Check if stats are correctly stored and can be decoded
        $stats = json_decode($pokemon->stats, true);
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('attack', $stats);
        $this->assertArrayHasKey('defense', $stats);
        $this->assertArrayHasKey('hp', $stats);
        $this->assertArrayhasKey('speed', $stats);

        // Verify specific values
        $this->assertEquals(84, $stats['attack']);
        $this->assertEquals(78, $stats['defense']);
        $this->assertEquals(78, $stats['hp']);
        $this->assertEquals(100, $stats['speed']);
    }
}
