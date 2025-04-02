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
    
    
}