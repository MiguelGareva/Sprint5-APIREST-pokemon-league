<?php

namespace Tests\Unit\Services;

use App\Models\Trainer;
use App\Services\RankingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RankingService $rankingService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create service instance
        $this->rankingService = new RankingService();

        // Create trainers with different points
        Trainer::factory()->create(['name' => 'Ash', 'points' => 100]);
        Trainer::factory()->create(['name' => 'Misty', 'points' => 150]);
        Trainer::factory()->create(['name' => 'Brock', 'points' => 120]);
        Trainer::factory()->create(['name' => 'Gary', 'points' => 200]);
        Trainer::factory()->create(['name' => 'May', 'points' => 80]);
    }

    public function test_it_can_get_full_ranking()
    {
        // Act
        $ranking = $this->rankingService->getFullRanking();

        // Assert
        $this->assertCount(5, $ranking);
        $this->assertEquals('Gary', $ranking->first()->name); // Highest points
        $this->assertEquals('May', $ranking->last()->name); // Lowest points
    }

    public function test_it_can_get_top_n_trainers()
    {
        // Act
        $topThree = $this->rankingService->getTopTrainers(3);

        // Assert
        $this->assertCount(3, $topThree);
        $this->assertEquals('Gary', $topThree->first()->name);
        $this->assertEquals('Brock', $topThree->last()->name);
    }

    public function test_it_can_get_trainer_rank()
    {
        // Arrange
        $ash = Trainer::where('name', 'Ash')->first();
        $gary = Trainer::where('name', 'Gary')->first();

        // Act
        $ashRank = $this->rankingService->getTrainerRank($ash);
        $garyRank = $this->rankingService->getTrainerRank($gary);

        // Assert
        $this->assertEquals(4, $ashRank); // Ash should be 4th
        $this->assertEquals(1, $garyRank); // Gary should be 1st
    }

    public function test_it_can_update_trainer_points()
    {
        // Arrange
        $ash = Trainer::where('name', 'Ash')->first();
        $initialPoints = $ash->points;

        // Act
        $this->rankingService->updateTrainerPoints($ash, 50);
        $ash->refresh();

        // Assert
        $this->assertEquals($initialPoints + 50, $ash->points);
    }

    public function test_it_can_get_rank_changes_between_periods()
    {
        // This is a more advanced test that would compare rankings from different periods
        // For demonstration, we'll simulate a points change and check the rank differences

        // Arrange - Initial state captured during setUp
        $initialRanking = $this->rankingService->getFullRanking()->pluck('name', 'id')->toArray();
        
        // Act - Update points to change ranks
        $ash = Trainer::where('name', 'Ash')->first();
        $this->rankingService->updateTrainerPoints($ash, 120); // This should move Ash to the top
        
        $newRanking = $this->rankingService->getFullRanking()->pluck('name', 'id')->toArray();
        
        // Calculate rank changes
        $rankChanges = $this->rankingService->calculateRankChanges($initialRanking, $newRanking);
        
        // Assert
        $this->assertArrayHasKey($ash->id, $rankChanges);
        $this->assertEquals(3, $rankChanges[$ash->id]); // Ash moved up 3 positions
    }

    public function test_it_can_get_trainers_with_similar_points()
    {
       // Arrange
    $ash = Trainer::where('name', 'Ash')->first();

    // Act
    $similarTrainers = $this->rankingService->getTrainersWithSimilarPoints($ash, 25);

    // Assert
    $this->assertCount(2, $similarTrainers);
    $this->assertContains('Brock', $similarTrainers->pluck('name'));
    $this->assertContains('May', $similarTrainers->pluck('name'));
    }
}