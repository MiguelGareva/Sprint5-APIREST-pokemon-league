<?php

namespace Tests\Unit\Services;

use App\Models\Battle;
use App\Models\Pokemon;
use App\Models\Trainer;
use App\Services\BattleService;
use App\Exceptions\BattleException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BattleService $battleService;
    protected Trainer $trainer1;
    protected Trainer $trainer2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create service instance
        $this->battleService = new BattleService();

        // Create trainers for testing
        $this->trainer1 = Trainer::factory()->create(['points' => 100]);
        $this->trainer2 = Trainer::factory()->create(['points' => 100]);

        // Create pokemons for trainers
        Pokemon::factory()->create([
            'trainer_id' => $this->trainer1->id,
            'level' => 5,
            'stats' => [
                'attack' => 50,
                'defense' => 40,
                'speed' => 60
            ]
        ]);

        Pokemon::factory()->create([
            'trainer_id' => $this->trainer2->id,
            'level' => 4,
            'stats' => [
                'attack' => 45,
                'defense' => 35,
                'speed' => 55
            ]
        ]);
    }

    public function test_it_can_create_a_battle()
    {
        // Arrange
        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $this->trainer2->id,
            'date' => Carbon::now()
        ];

        // Act
        $battle = $this->battleService->createBattle($battleData);

        // Assert
        $this->assertInstanceOf(Battle::class, $battle);
        $this->assertEquals($this->trainer1->id, $battle->trainer1_id);
        $this->assertEquals($this->trainer2->id, $battle->trainer2_id);
        $this->assertNotNull($battle->winner_id); // A winner should be determined
    }

    public function test_it_cannot_create_battle_with_same_trainer()
    {
        // Arrange
        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $this->trainer1->id, // Same trainer
            'date' => Carbon::now()
        ];

        // Act & Assert
        $this->expectException(BattleException::class);
        $this->battleService->createBattle($battleData);
    }

    public function test_it_cannot_create_battle_if_trainer_has_no_pokemons()
    {
        // Arrange
        $trainerWithoutPokemons = Trainer::factory()->create();
        
        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $trainerWithoutPokemons->id,
            'date' => Carbon::now()
        ];

        // Act & Assert
        $this->expectException(BattleException::class);
        $this->battleService->createBattle($battleData);
    }

    public function test_it_determines_winner_correctly()
    {
        // Arrange
        // Create a stronger pokemon for trainer1
        Pokemon::factory()->create([
            'trainer_id' => $this->trainer1->id,
            'level' => 10,
            'stats' => [
                'attack' => 100,
                'defense' => 80,
                'speed' => 90
            ]
        ]);

        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $this->trainer2->id,
            'date' => Carbon::now()
        ];

        // Act
        $battle = $this->battleService->createBattle($battleData);
        
        // Assert - Trainer1 should win as they have stronger pokemons
        $this->assertEquals($this->trainer1->id, $battle->winner_id);
    }

    public function test_it_updates_points_after_battle()
    {
        // Arrange
        $initialPoints1 = $this->trainer1->points;
        $initialPoints2 = $this->trainer2->points;

        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $this->trainer2->id,
            'date' => Carbon::now()
        ];

        // Act
        $battle = $this->battleService->createBattle($battleData);
        
        // Refresh trainers from database
        $this->trainer1->refresh();
        $this->trainer2->refresh();

        // Assert
        if ($battle->winner_id == $this->trainer1->id) {
            $this->assertGreaterThan($initialPoints1, $this->trainer1->points);
            $this->assertLessThan($initialPoints2, $this->trainer2->points);
        } else {
            $this->assertLessThan($initialPoints1, $this->trainer1->points);
            $this->assertGreaterThan($initialPoints2, $this->trainer2->points);
        }
    }

    public function test_it_can_simulate_battle_without_saving()
    {
        // Act
        $result = $this->battleService->simulateBattle($this->trainer1, $this->trainer2);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('winner', $result);
        $this->assertArrayHasKey('loser', $result);
        $this->assertArrayHasKey('points_change', $result);
        $this->assertArrayHasKey('battle_details', $result);
    }

    public function test_it_can_delete_battle_and_revert_points()
    {
        // Arrange
        $battleData = [
            'trainer1_id' => $this->trainer1->id,
            'trainer2_id' => $this->trainer2->id,
            'date' => Carbon::now()
        ];
        
        $battle = $this->battleService->createBattle($battleData);
        
        // Save original points after battle
        $trainer1PointsAfterBattle = $this->trainer1->fresh()->points;
        $trainer2PointsAfterBattle = $this->trainer2->fresh()->points;

        // Act
        $this->battleService->deleteBattle($battle);
        
        // Refresh trainers
        $this->trainer1->refresh();
        $this->trainer2->refresh();

        // Assert
        // Points should be restored to original values
        $this->assertEquals(100, $this->trainer1->points);
        $this->assertEquals(100, $this->trainer2->points);
    }

    public function test_it_calculates_pokemon_strength_correctly()
    {
        // Arrange
        $pokemon = Pokemon::factory()->create([
            'level' => 10,
            'stats' => [
                'attack' => 80,
                'defense' => 60,
                'speed' => 70
            ]
        ]);

        // Act - Use reflection to access protected method
        $method = new \ReflectionMethod(BattleService::class, 'calculatePokemonStrength');
        $method->setAccessible(true);
        $strength = $method->invoke($this->battleService, $pokemon);

        // Assert
        // Test that strength is calculated as expected
        // Formula: level * (0.5*attack + 0.3*defense + 0.2*speed)
        $expectedStrength = 10 * (0.5 * 80 + 0.3 * 60 + 0.2 * 70);
        $this->assertEquals($expectedStrength, $strength);
    }
}