<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainerRequest;
use App\Models\Trainer;
use App\Services\RankingService;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * The ranking service instance.
     *
     * @var \App\Services\RankingService
     */
    protected $rankingService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\RankingService $rankingService
     * @return void
     */
    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Display a listing of trainers.
     */
    public function index()
    {
        $trainers = Trainer::all();

        return response()->json([
            'data' => $trainers
        ]);
    }

    /**
     * Store a newly created trainer in storage.
     */
    public function store(TrainerRequest $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $data = $request->validated();
        if (!isset($data['points'])) {
            $data['points'] = 0;
        }
        
        $trainer = Trainer::create($data);

        return response()->json([
            'data' => $trainer
        ], 201);
    }

    /**
     * Display the specified trainer with their pokemons.
     */
    public function show(Trainer $trainer)
    {
        $trainer->load('pokemons');

        // Get the trainer's rank in the global ranking
        $rank = $this->rankingService->getTrainerRank($trainer);

        return response()->json([
            'data' => $trainer,
            'rank' => $rank
        ]);
    }

    /**
     * Update the specified trainer in storage.
     */
    public function update(TrainerRequest $request, Trainer $trainer)
    {
        $trainer->update($request->validated());

        return response()->json([
            'data' => $trainer
        ]);
    }

    /**
     * Remove the specified trainer from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();

        return response()->json([
            'message' => 'Trainer deleted successfully'
        ]);
    }

    /**
     * Display trainers ranking ordered by points.
     */
    public function ranking()
    {
        $ranking = $this->rankingService->getFullRanking();

        return response()->json([
            'data' => $ranking
        ]);
    }

    /**
     * Display top N trainers.
     * 
     * @param int $count Number of top trainers to retrieve
     */
    public function topTrainers($count = 10)
    {
        $count = min(50, max(1, (int)$count)); // Limit between 1 and 50
        $topTrainers = $this->rankingService->getTopTrainers($count);

        return response()->json([
            'data' => $topTrainers
        ]);
    }

    /**
     * Get trainers with similar points to the specified trainer.
     */
    public function similarTrainers(Trainer $trainer, $range = 30)
    {
        $range = min(100, max(1, (int)$range)); // Limit between 1 and 100
        $similarTrainers = $this->rankingService->getTrainersWithSimilarPoints($trainer, $range);

        return response()->json([
            'data' => $similarTrainers
        ]);
    }

    /**
     * Get monthly ranking statistics.
     */
    public function monthlyStats()
    {
        $stats = $this->rankingService->getMonthlyStats();

        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Update trainer points (admin only).
     */
    public function updatePoints(Request $request, Trainer $trainer)
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate request
        $validated = $request->validate([
            'points_change' => 'required|integer',
        ]);

        // Update trainer points
        $this->rankingService->updateTrainerPoints($trainer, $validated['points_change']);

        return response()->json([
            'message' => 'Trainer points updated successfully',
            'trainer' => $trainer->fresh()
        ]);
    }
}