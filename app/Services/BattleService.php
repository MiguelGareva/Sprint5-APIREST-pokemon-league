<?php

namespace App\Services;

use App\Exceptions\BattleException;
use App\Models\Battle;
use App\Models\Pokemon;
use App\Models\Trainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BattleService
{
    /**
     * Points awarded to the winner of a battle
     */
    const POINTS_AWARDED = 3;

    /**
     * Create a new battle between two trainers
     *
     * @param array $battleData Battle data with trainer1_id, trainer2_id, and date
     * @return Battle The created battle with determined winner
     * @throws BattleException If battle creation fails
     */
    public function createBattle(array $battleData): Battle
    {
        try {
            // Validate trainers
            $trainer1 = Trainer::findOrFail($battleData['trainer1_id']);
            $trainer2 = Trainer::findOrFail($battleData['trainer2_id']);

            $this->validateBattle($trainer1, $trainer2);

            // Determine winner
            $trainer1Strength = $this->calculateTrainerStrength($trainer1);
            $trainer2Strength = $this->calculateTrainerStrength($trainer2);
            
            // Add some randomness
            $trainer1Strength += rand(1, 20);
            $trainer2Strength += rand(1, 20);
            
            $winner = ($trainer1Strength >= $trainer2Strength) ? $trainer1 : $trainer2;
            $loser = ($winner->id === $trainer1->id) ? $trainer2 : $trainer1;

            // Start database transaction
            DB::beginTransaction();

            // Create battle record
            $battle = new Battle([
                'trainer1_id' => $trainer1->id,
                'trainer2_id' => $trainer2->id,
                'date' => $battleData['date'] ?? now(),
                'winner_id' => $winner->id
            ]);
            $battle->save();

            // Update trainer points
            $winner->points += self::POINTS_AWARDED;
            $winner->save();

            DB::commit();

            return $battle;
        } catch (BattleException $e) {
            // Re-throw BattleException
            throw $e;
        } catch (\Exception $e) {
            // Rollback transaction on error
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error('Battle creation failed: ' . $e->getMessage());
            throw new BattleException('Failed to create battle: ' . $e->getMessage());
        }
    }

    /**
     * Delete a battle and revert trainer points
     *
     * @param Battle $battle The battle to delete
     * @return bool True if the battle was deleted
     * @throws BattleException If battle deletion fails
     */
    public function deleteBattle(Battle $battle): bool
    {
        try {
            DB::beginTransaction();

            // Load battle with winner
            $battle->load('winner');

            // Only revert points if winner is determined
            if ($battle->winner_id) {
                $winner = $battle->winner;
                $winner->points -= self::POINTS_AWARDED;
                $winner->save();
            }

            // Delete battle
            $result = $battle->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Battle deletion failed: ' . $e->getMessage());
            throw new BattleException('Failed to delete battle: ' . $e->getMessage());
        }
    }

    /**
     * Simulate a battle between two trainers without saving
     *
     * @param Trainer $trainer1 First trainer
     * @param Trainer $trainer2 Second trainer
     * @return array Battle simulation results
     * @throws BattleException If simulation fails
     */
    public function simulateBattle(Trainer $trainer1, Trainer $trainer2): array
    {
        $this->validateBattle($trainer1, $trainer2);

        // Calculate strengths
        $strength1 = $this->calculateTrainerStrength($trainer1);
        $strength2 = $this->calculateTrainerStrength($trainer2);
        
        // Add randomness for simulation
        $strength1 += rand(1, 20);
        $strength2 += rand(1, 20);

        // Determine winner and loser
        if ($strength1 > $strength2) {
            $winner = $trainer1;
            $loser = $trainer2;
        } else {
            $winner = $trainer2;
            $loser = $trainer1;
        }

        // Return simulation results
        return [
            'winner' => $winner,
            'loser' => $loser,
            'points_change' => self::POINTS_AWARDED,
            'battle_details' => [
                'trainer1_strength' => $strength1,
                'trainer2_strength' => $strength2
            ]
        ];
    }

    /**
     * Validate battle conditions
     *
     * @param Trainer $trainer1 First trainer
     * @param Trainer $trainer2 Second trainer
     * @throws BattleException If validation fails
     */
    protected function validateBattle(Trainer $trainer1, Trainer $trainer2): void
    {
        // Check if it's the same trainer
        if ($trainer1->id === $trainer2->id) {
            throw new BattleException(
                'A trainer cannot battle against themselves',
                BattleException::ERROR_SAME_TRAINER
            );
        }

        // Load trainers with pokemons
        $trainer1->load('pokemons');
        $trainer2->load('pokemons');

        // Check if trainers have pokemons
        if ($trainer1->pokemons->isEmpty()) {
            throw new BattleException(
                "Trainer '{$trainer1->name}' has no pokemons",
                BattleException::ERROR_NO_POKEMONS
            );
        }

        if ($trainer2->pokemons->isEmpty()) {
            throw new BattleException(
                "Trainer '{$trainer2->name}' has no pokemons",
                BattleException::ERROR_NO_POKEMONS
            );
        }
    }

    /**
     * Calculate the total strength of a trainer based on their pokemons
     *
     * @param Trainer $trainer The trainer to calculate strength for
     * @return float The total strength of the trainer
     */
    protected function calculateTrainerStrength(Trainer $trainer): float
    {
        $totalStrength = 0;

        foreach ($trainer->pokemons as $pokemon) {
            $totalStrength += $this->calculatePokemonStrength($pokemon);
        }

        return $totalStrength;
    }

    /**
     * Calculate the strength of a pokemon based on level and stats
     *
     * @param Pokemon $pokemon The pokemon to calculate strength for
     * @return float The strength of the pokemon
     */
    protected function calculatePokemonStrength(Pokemon $pokemon): float
    {
        // Get stats from the json field
        $stats = $pokemon->stats;
        
        // Ensure stats is in the correct format for array_sum
        if (is_string($stats)) {
            $stats = json_decode($stats, true);
        } elseif (is_object($stats)) {
            $stats = (array) $stats;
        }
        
        // Check if stats is still not an array
        if (!is_array($stats)) {
            // Log for debugging
            \Log::warning('Pokemon stats not in expected format', [
                'pokemon_id' => $pokemon->id,
                'stats_type' => gettype($stats),
                'stats_value' => $stats
            ]);
            
            // Return a default value or 0 to avoid error
            return 0;
        }
        
        // Sum up all stats
        $statSum = array_sum($stats);
        
        // Use the original formula
        return $statSum * $pokemon->level / 10;
    }
}