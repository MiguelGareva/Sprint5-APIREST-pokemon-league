<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainerRequest;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        
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

        return response()->json([
            'data' => $trainer
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
        $ranking = Trainer::orderBy('points', 'desc')->get(['id', 'name', 'points']);

        return response()->json([
            'data' => $ranking
        ]);
    }
}