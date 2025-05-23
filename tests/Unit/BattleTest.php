<?php

namespace Tests\Unit;

use App\Models\Battle;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleTest extends TestCase{

    use RefreshDatabase;

    public function testBattleBelongsToTwoTrainers(){

        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();

        $battle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id
        ]);

        $this->assertInstanceOf(Trainer::class, $battle->trainer1);
        $this->assertInstanceOf(Trainer::class, $battle->trainer2);
        $this->assertEquals($trainer1->id, $battle->trainer1->id);
        $this->assertEquals($trainer2->id, $battle->trainer2->id);
    }

    public function testBattleHasWinner(){

        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();

        $battle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'winner_id' => $trainer1->id
        ]);

        $this->assertInstanceOf(Trainer::class, $battle->winner);
        $this->assertEquals($trainer1->id, $battle->winner->id);
    }

    public function testBattleCanBeADraw(){

        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();

        $battle = Battle::factory()->create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'winner_id' => null
        ]);

        $this->assertNull($battle->winner);
    }

    public function testBattleHasDate(){

        $battle = Battle::factory()->create([
            'date' => '2023-04-02 10:00:00'
        ]);

        $this->assertEquals('2023-04-02 10:00:00', $battle->date);
    }
}