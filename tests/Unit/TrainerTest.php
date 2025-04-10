<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Pokemon;
use App\Models\Battle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use RefreshDatabase;

    public function testTrainerBelongsToUser()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $trainer->user);
        $this->assertEquals($user->id, $trainer->user->id);
    }

    public function testTrainerCanHavePokemons()
    {
        $trainer = Trainer::factory()->create();
        $pokemon1 = Pokemon::factory()->create(['trainer_id' => $trainer->id]);
        $pokemon2 = Pokemon::factory()->create(['trainer_id' => $trainer->id]);
        
        $this->assertInstanceOf(Pokemon::class, $trainer->pokemons->first());
        $this->assertCount(2, $trainer->pokemons);
    }
    
    public function testTrainerCanHaveMaximumOfThreePokemons()
    {
        $trainer = Trainer::factory()->create();
        
        // Add 3 pokemons (maximum allowed)
        Pokemon::factory()->count(3)->create(['trainer_id' => $trainer->id]);
        
        $this->assertCount(3, $trainer->pokemons);
        $this->assertTrue($trainer->canAddMorePokemons() === false);
        
        // Try to add a 4th pokemon
        $extraPokemon = Pokemon::factory()->create();
        $result = $trainer->addPokemon($extraPokemon);
        
        // Should fail and not be added
        $this->assertFalse($result);
        $this->assertCount(3, $trainer->fresh()->pokemons);
    }

    public function testTrainerCanParticipateInBattles()
    {
        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();
        
        $battle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
        ]);
        
        $this->assertInstanceOf(Battle::class, $trainer1->battlesAsTrainer1->first());
        $this->assertInstanceOf(Battle::class, $trainer2->battlesAsTrainer2->first());
        $this->assertCount(1, $trainer1->battles());
        $this->assertCount(1, $trainer2->battles());
    }
}