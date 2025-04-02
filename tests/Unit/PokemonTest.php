<?php 

namespace Tests\Unit;

use App\Models\Pokemon;
Use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonTest extends TestCase{

    use RefreshDatabase;

    /** @test */
    public function testPokemonBelongsToTrainer(){

        $trainer = Trainer::factory()->create();
        $pokemon = Pokemon::factory()->create(['trainer_id' => $trainer->id]);

        $this->assertInstanceOf(Trainer::class, $pokemon->trainer);
        $this->assertEquals($trainer->id, $pokemon->trainer->id);
    }

    /** @test */
    public function testPokemonCanExistsWithoutTrainer(){

        $pokemon = Pokemon::factory()->create(['trainer_id' => null]);

        $this->assertNull($pokemon->trainer);
    }

    /** @test */
    public function testPokemonHasStats(){

        $stats = [
            'hp' => 50,
            'attack' => 55,
            'defense' => 40,
            'special_attack' => 50,
            'special_defense' => 50,
            'speed' => 55
        ];

        $pokemon = Pokemon::factory()->create(['stats' => json_encode($stats)]);

        $this->assertEquals($stats, json_decode($pokemon->stats, true));
    }
}