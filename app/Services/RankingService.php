<?php

namespace App\Services;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Cache key for full ranking
     */
    const CACHE_KEY_FULL_RANKING = 'trainer_full_ranking';
    
    /**
     * Cache key prefix for top trainers
     */
    const CACHE_KEY_TOP_TRAINERS = 'trainer_top_';
    
    /**
     * Cache TTL in seconds (10 minutes)
     */
    const CACHE_TTL = 600;

    /**
     * Get the full ranking of trainers by points
     *
     * @param bool $useCache Whether to use cached results
     * @return Collection Collection of trainers ordered by points
     */
    public function getFullRanking(bool $useCache = true): Collection
    {
        if ($useCache && Cache::has(self::CACHE_KEY_FULL_RANKING)) {
            return Cache::get(self::CACHE_KEY_FULL_RANKING);
        }

        $ranking = Trainer::orderBy('points', 'desc')->get(['id', 'name', 'points']);
        
        if ($useCache) {
            Cache::put(self::CACHE_KEY_FULL_RANKING, $ranking, self::CACHE_TTL);
        }
        
        return $ranking;
    }

    /**
     * Get the top N trainers by points
     *
     * @param int $count Number of trainers to retrieve
     * @param bool $useCache Whether to use cached results
     * @return Collection Collection of top N trainers
     */
    public function getTopTrainers(int $count, bool $useCache = true): Collection
    {
        $cacheKey = self::CACHE_KEY_TOP_TRAINERS . $count;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $topTrainers = Trainer::orderBy('points', 'desc')
            ->limit($count)
            ->get(['id', 'name', 'points']);
        
        if ($useCache) {
            Cache::put($cacheKey, $topTrainers, self::CACHE_TTL);
        }
        
        return $topTrainers;
    }

    /**
     * Get the rank of a trainer in the global ranking
     *
     * @param Trainer $trainer The trainer to get the rank for
     * @return int The rank of the trainer (1-based)
     */
    public function getTrainerRank(Trainer $trainer): int
    {
        // Count trainers with more points than the given trainer
        $higherRankedCount = Trainer::where('points', '>', $trainer->points)->count();
        
        // Add 1 to get 1-based ranking
        return $higherRankedCount + 1;
    }

    /**
     * Update a trainer's points
     *
     * @param Trainer $trainer The trainer to update
     * @param int $pointsChange The points to add (positive) or subtract (negative)
     * @return bool True if the update was successful
     */
    public function updateTrainerPoints(Trainer $trainer, int $pointsChange): bool
    {
        try {
            // Update trainer points
            $trainer->points += $pointsChange;
            $result = $trainer->save();
            
            // Clear ranking caches
            $this->clearRankingCaches();
            
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get trainers with points similar to the given trainer
     *
     * @param Trainer $trainer The reference trainer
     * @param int $pointsRange The range of points difference to consider
     * @return Collection Collection of trainers with similar points
     */
    public function getTrainersWithSimilarPoints(Trainer $trainer, int $pointsRange): Collection
    {
        return Trainer::where('id', '!=', $trainer->id)
            ->whereBetween('points', [
                $trainer->points - $pointsRange,
                $trainer->points + $pointsRange
            ])
            ->orderBy('points', 'desc')
            ->get(['id', 'name', 'points']);
    }

    /**
     * Calculate rank changes between two rankings
     *
     * @param array $oldRanking Old ranking array (id => name)
     * @param array $newRanking New ranking array (id => name)
     * @return array Associative array of trainer IDs and their rank changes
     */
    public function calculateRankChanges(array $oldRanking, array $newRanking): array
    {
        $rankChanges = [];
        
        // Create arrays with trainer positions
        $oldPositions = array_flip(array_keys($oldRanking));
        $newPositions = array_flip(array_keys($newRanking));
        
        // Calculate position changes for each trainer
        foreach ($oldPositions as $trainerId => $oldPosition) {
            if (isset($newPositions[$trainerId])) {
                $newPosition = $newPositions[$trainerId];
                $change = $oldPosition - $newPosition; // Positive means improved rank
                $rankChanges[$trainerId] = $change;
            }
        }
        
        return $rankChanges;
    }

    /**
     * Get monthly ranking statistics
     *
     * @return array Array of monthly statistics
     */
    public function getMonthlyStats(): array
    {
        // Get the current month start and end
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        // Get the trainers with the most battles this month
        $mostActiveBattles = DB::table('battles')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('trainer1_id as trainer_id'),
                DB::raw('COUNT(*) as battle_count')
            )
            ->groupBy('trainer1_id')
            ->union(
                DB::table('battles')
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->select(
                        DB::raw('trainer2_id as trainer_id'),
                        DB::raw('COUNT(*) as battle_count')
                    )
                    ->groupBy('trainer2_id')
            )
            ->orderBy('battle_count', 'desc')
            ->limit(5)
            ->get();
            
        // Get the trainers with the most wins this month
        $mostWins = DB::table('battles')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('winner_id')
            ->select('winner_id', DB::raw('COUNT(*) as win_count'))
            ->groupBy('winner_id')
            ->orderBy('win_count', 'desc')
            ->limit(5)
            ->get();
            
        // Get trainer names for the IDs
        $trainerNames = Trainer::whereIn('id', 
            collect($mostActiveBattles)->pluck('trainer_id')
                ->concat(collect($mostWins)->pluck('winner_id'))
                ->unique()
        )->pluck('name', 'id');
        
        return [
            'period' => [
                'start' => $startOfMonth->format('Y-m-d'),
                'end' => $endOfMonth->format('Y-m-d'),
            ],
            'most_active' => collect($mostActiveBattles)->map(function ($item) use ($trainerNames) {
                return [
                    'trainer_id' => $item->trainer_id,
                    'trainer_name' => $trainerNames[$item->trainer_id] ?? 'Unknown',
                    'battle_count' => $item->battle_count,
                ];
            }),
            'most_wins' => collect($mostWins)->map(function ($item) use ($trainerNames) {
                return [
                    'trainer_id' => $item->winner_id,
                    'trainer_name' => $trainerNames[$item->winner_id] ?? 'Unknown',
                    'win_count' => $item->win_count,
                ];
            }),
        ];
    }

    /**
     * Clear all ranking-related caches
     */
    protected function clearRankingCaches(): void
    {
        Cache::forget(self::CACHE_KEY_FULL_RANKING);
        
        // Clear top trainers caches for common values
        foreach ([3, 5, 10, 20] as $count) {
            Cache::forget(self::CACHE_KEY_TOP_TRAINERS . $count);
        }
    }
}