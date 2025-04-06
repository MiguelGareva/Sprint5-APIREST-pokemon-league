<?php

namespace Tests\Unit;

use App\Models\Pokemon;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testPokemonCanBelongToTrainer()
    {
        $trainer = Trainer::factory()->create();
        $pokemon = Pokemon::factory()->create(['trainer_id' => $trainer->id]);
        
        $this->assertInstanceOf(Trainer::class, $pokemon->trainer);
        $this->assertEquals($trainer->id, $pokemon->trainer->id);
    }

    /** @test */
    public function testPokemonCanExistWithoutTrainer()
    {
        $pokemon = Pokemon::factory()->create(['trainer_id' => null]);
        
        $this->assertNull($pokemon->trainer);
    }

    /** @test */
    public function testPokemonHasStats()
    {
        $stats = [
            'hp' => 45,
            'attack' => 49,
            'defense' => 49,
            'special_attack' => 65,
            'special_defense' => 65,
            'speed' => 45
        ];
        
        $pokemon = Pokemon::factory()->create([
            'stats' => json_encode($stats)
        ]);
        
        $this->assertEquals($stats, json_decode($pokemon->stats, true));
    }
}