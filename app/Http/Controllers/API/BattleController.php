<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BattleRequest;
use App\Models\Battle;
use App\Services\BattleService;
use Illuminate\Http\Request;

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
        $battles = Battle::orderBy('date', 'desc')->get();

        return response()->json([
            'data' => $battles
        ]);
    }

    /**
     * Store a newly created battle in storage.
     */
    public function store(BattleRequest $request)
    {
        try {
            $battle = $this->battleService->createBattle($request->validated());
            
            return response()->json([
                'data' => $battle,
                'message' => 'Battle created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified battle with trainers.
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
     */
    public function destroy(Battle $battle)
    {
        $this->battleService->deleteBattle($battle);

        return response()->json([
            'message' => 'Battle deleted successfully'
        ]);
    }
}