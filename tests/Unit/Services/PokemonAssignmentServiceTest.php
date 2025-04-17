<?php

namespace Tests\Unit\Services;

use App\Models\Pokemon;
use App\Models\Trainer;
use App\Services\PokemonAssignmentService;
use App\Exceptions\PokemonAssignmentException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PokemonAssignmentService $service;
    protected Trainer $trainer;
    protected Pokemon $pokemon;

    protected function setUp(): void
    {
        parent::setUp();

        // Create service instance
        $this->service = new PokemonAssignmentService();

        // Create a trainer for testing
        $this->trainer = Trainer::factory()->create();

        // Create a pokemon without a trainer
        $this->pokemon = Pokemon::factory()->create([
            'trainer_id' => null
        ]);
    }

    public function test_it_can_assign_pokemon_to_trainer()
    {
        // Act
        $result = $this->service->assignPokemon($this->pokemon, $this->trainer);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($this->trainer->id, $this->pokemon->fresh()->trainer_id);
    }

    public function test_it_cannot_assign_pokemon_if_trainer_has_max_pokemons()
    {
        // Arrange - Create max pokemons for the trainer
        Pokemon::factory()->count(Trainer::MAX_POKEMONS)->create([
            'trainer_id' => $this->trainer->id
        ]);

        // Assert and Act
        $this->expectException(PokemonAssignmentException::class);
        $this->service->assignPokemon($this->pokemon, $this->trainer);

        // Verify pokemon wasn't assigned
        $this->assertNull($this->pokemon->fresh()->trainer_id);
    }

    public function test_it_can_release_pokemon_from_trainer()
    {
        // Arrange
        $pokemon = Pokemon::factory()->create([
            'trainer_id' => $this->trainer->id
        ]);

        // Act
        $result = $this->service->releasePokemon($pokemon);

        // Assert
        $this->assertTrue($result);
        $this->assertNull($pokemon->fresh()->trainer_id);
    }

    public function test_it_cannot_release_pokemon_that_doesnt_belong_to_a_trainer()
    {
        // Act & Assert
        $this->expectException(PokemonAssignmentException::class);
        $this->service->releasePokemon($this->pokemon);
    }

    public function test_it_can_transfer_pokemon_between_trainers()
    {
        // Arrange
        $pokemon = Pokemon::factory()->create([
            'trainer_id' => $this->trainer->id
        ]);
        $newTrainer = Trainer::factory()->create();

        // Act
        $result = $this->service->transferPokemon($pokemon, $newTrainer);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($newTrainer->id, $pokemon->fresh()->trainer_id);
    }

    public function test_it_cannot_transfer_pokemon_if_new_trainer_has_max_pokemons()
    {
        // Arrange
        $pokemon = Pokemon::factory()->create([
            'trainer_id' => $this->trainer->id
        ]);
        $newTrainer = Trainer::factory()->create();
        
        // Create max pokemons for new trainer
        Pokemon::factory()->count(Trainer::MAX_POKEMONS)->create([
            'trainer_id' => $newTrainer->id
        ]);

        // Act & Assert
        $this->expectException(PokemonAssignmentException::class);
        $this->service->transferPokemon($pokemon, $newTrainer);

        // Verify pokemon wasn't transferred
        $this->assertEquals($this->trainer->id, $pokemon->fresh()->trainer_id);
    }

    public function test_it_can_list_available_pokemons()
    {
        // Arrange - Create some more available pokemons
        Pokemon::factory()->count(3)->create([
            'trainer_id' => null
        ]);

        // Act
        $availablePokemons = $this->service->getAvailablePokemons();

        // Assert
        $this->assertEquals(4, $availablePokemons->count()); // 3 new + 1 from setUp
        $this->assertNull($availablePokemons->first()->trainer_id);
    }

    public function test_it_can_list_trainer_pokemons()
    {
        // Arrange - Create some pokemons for the trainer
        Pokemon::factory()->count(2)->create([
            'trainer_id' => $this->trainer->id
        ]);

        // Act
        $trainerPokemons = $this->service->getTrainerPokemons($this->trainer);

        // Assert
        $this->assertEquals(2, $trainerPokemons->count());
        $this->assertEquals($this->trainer->id, $trainerPokemons->first()->trainer_id);
    }
}