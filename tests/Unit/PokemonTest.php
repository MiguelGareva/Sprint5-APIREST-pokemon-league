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
}