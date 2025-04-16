<?php

namespace App\Services;

use App\Models\Battle;
use App\Models\Trainer;
use Exception;

class BattleService
{
    /**
     * Points awarded to the winner of a battle.
     */
    const WINNER_POINTS = 3;

    /**
     * Create and perform a new battle between trainers.
     *
     * @param array $data
     * @return \App\Models\Battle
     * @throws \Exception
     */
    public function createBattle(array $data): Battle
    {
        // Get the trainers
        $trainer1 = Trainer::findOrFail($data['trainer1_id']);
        $trainer2 = Trainer::findOrFail($data['trainer2_id']);
        
        // Check if trainers are the same
        if ($trainer1->id === $trainer2->id) {
            throw new Exception('A trainer cannot battle against themselves');
        }
        
        // Check if trainers have pokemons
        if ($trainer1->pokemons()->count() === 0 || $trainer2->pokemons()->count() === 0) {
            throw new Exception('Both trainers must have at least one pokemon');
        }
        
        // Determine winner (simplified logic - this would be more complex in a real app)
        $winner = $this->determineWinner($trainer1, $trainer2);
        
        // Update trainer points
        $winner->increment('points', self::WINNER_POINTS);
        
        // Create the battle record
        $battle = Battle::create([
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'winner_id' => $winner->id,
            'date' => $data['date'] ?? now(),
        ]);
        
        return $battle;
    }
    
    /**
     * Delete a battle and revert point changes.
     *
     * @param \App\Models\Battle $battle
     * @return void
     */
    public function deleteBattle(Battle $battle): void
    {
        // Revert points for the winner
        if ($battle->winner_id) {
            $winner = Trainer::find($battle->winner_id);
            if ($winner) {
                $winner->decrement('points', self::WINNER_POINTS);
            }
        }
        
        // Delete the battle
        $battle->delete();
    }
    
    /**
     * Determine the winner of a battle.
     *
     * @param \App\Models\Trainer $trainer1
     * @param \App\Models\Trainer $trainer2
     * @return \App\Models\Trainer
     */
    protected function determineWinner(Trainer $trainer1, Trainer $trainer2): Trainer
    {
        // Calculate total power for each trainer
        $power1 = $this->calculateTrainerPower($trainer1);
        $power2 = $this->calculateTrainerPower($trainer2);
        
        // Add some randomness
        $power1 += rand(1, 20);
        $power2 += rand(1, 20);
        
        // Return the winner
        return ($power1 >= $power2) ? $trainer1 : $trainer2;
    }
    
    /**
     * Calculate the total power of a trainer's pokemons.
     *
     * @param \App\Models\Trainer $trainer
     * @return int
     */
    protected function calculateTrainerPower(Trainer $trainer): int
    {
        $totalPower = 0;
        
        foreach ($trainer->pokemons as $pokemon) {
            // Sum up stats and multiply by level
            $stats = $pokemon->stats;
            $statSum = array_sum($stats);
            $power = $statSum * $pokemon->level / 10;
            
            $totalPower += $power;
        }
        
        return (int) $totalPower;
    }
}