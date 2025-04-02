<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Pokemon;
use App\Models\Battle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase{

    use RefreshDatabase;

    /** @test */
    public function testTrainerBelongsToUser()
     {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create(['user_id' => $user->id]);
         
        $this->assertInstanceOf(User::class, $trainer->user);
        $this->assertEquals($user->id, $trainer->user->id);
     }

    /** @test */
    public function testTrainerCanHavePokemons(){

        $trainer = Trainer::factory()->create();
        $pokemon1 = Pokemon::factory()->create(['trainer_id' =>$trainer->id]);
        $pokemon2 = POkemon::factory()->create(['trainer_id' =>$trainer->id]);

        $this->assertInstanceOf(Pokemon::class, $trainer->pokemons->first());
        $this->assertCount(2, $trainer->pokemons);
     }
    
    /** @test */
    public function testTrainerCanHaveMaximumOfThreePokemons(){

        $trainer = Trainer::factory()->create();

        // Add 3 pokemons (maximum allowed)
        Pokemon::factory()->count(3)->create(['trainer_id' => $trainer->id]);

        $this->assertCount(3, $trainer->pokemons);
        $this->assertTrue($trainer->canAddMorePOkemons() === false);

        //Try to add a 4th pokemon
        $extraPokemon = Pokemon::factory()->create();
        $result = $trainer->addPokemon($extraPokemon);

        //Should fail and not be added
        $this->assertFalse($result);
        $this->assertCount(3, $trainer->fresh()->pokemons);
    }

    /** @test */
    public function testTrainerCanParticipateInBattles(){

        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();

        $batlle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
        ]);

        $this->assertInstanceOf(Battle::class, $trainer1->battleAsTrainer1->first());
        $this->assertInstanceOf(Battle::class, $trainer2->battleAsTrainer2->first());
        $this->assertCount(1, $trainer1->battles());
        $this->assertCount(1, $trainer2->battles());
    }
}