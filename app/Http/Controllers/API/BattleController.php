<?php

namespace App\Http\Controllers\API;

use App\Exceptions\BattleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BattleRequest;
use App\Http\Requests\SimulateBattleRequest;
use App\Models\Battle;
use App\Models\Trainer;
use App\Services\BattleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BattleController extends Controller
{
    /**
     * The battle service instance.
     *
     * @var \App\Services\BattleService
     */
    protected $battleService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\BattleService $battleService
     * @return void
     */
    public function __construct(BattleService $battleService)
    {
        $this->battleService = $battleService;
    }

    /**
     * Display a listing of battles.
     */
    public function index()
    {
        $battles = Battle::with(['trainer1:id,name', 'trainer2:id,name', 'winner:id,name'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'data' => $battles
        ]);
    }

    /**
     * Store a newly created battle in storage.
     *
     * @param \App\Http\Requests\BattleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BattleRequest $request)
    {
        \Log::info('Store battle method called', [
            'user' => auth()->user(),
            'request_data' => $request->all()
        ]);
        
        try {
            $battle = $this->battleService->createBattle($request->validated());
            
            return response()->json([
                'data' => $battle,
                'message' => 'Battle created successfully'
            ], 201);
        } catch (BattleException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to create battle: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to create battle'
            ], 500);
        }
    }

    /**
     * Display the specified battle with trainers.
     *
     * @param \App\Models\Battle $battle
     * @return \Illuminate\Http\Response
     */
    public function show(Battle $battle)
    {
        $battle->load(['trainer1', 'trainer2', 'winner']);

        return response()->json([
            'data' => $battle
        ]);
    }

    /**
     * Remove the specified battle from storage and update points.
     *
     * @param \App\Models\Battle $battle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Battle $battle)
    {
        try {
            $this->battleService->deleteBattle($battle);

            return response()->json([
                'message' => 'Battle deleted successfully'
            ]);
        } catch (BattleException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to delete battle: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to delete battle'
            ], 500);
        }
    }

    /**
     * Simulate a battle between two trainers without saving results.
     *
     * @param \App\Http\Requests\SimulateBattleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function simulateBattle(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'trainer1_id' => 'required|exists:trainers,id',
                'trainer2_id' => 'required|exists:trainers,id|different:trainer1_id',
            ]);

            $trainer1 = Trainer::findOrFail($validated['trainer1_id']);
            $trainer2 = Trainer::findOrFail($validated['trainer2_id']);

            $result = $this->battleService->simulateBattle($trainer1, $trainer2);

            return response()->json([
                'data' => $result
            ]);
        } catch (BattleException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to simulate battle: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to simulate battle'
            ], 500);
        }
    }

    /**
     * Get recent battles with optional filters.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $limit
     * @return \Illuminate\Http\Response
     */
    public function getRecentBattles(Request $request, $limit = 10)
    {
        try {
            $limit = min(50, max(1, (int)$limit)); // Limit between 1 and 50
            $trainerId = $request->query('trainer_id');

            $battles = $this->battleService->getRecentBattles($limit, $trainerId);

            return response()->json([
                'data' => $battles
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get recent battles: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to get recent battles'
            ], 500);
        }
    }
}