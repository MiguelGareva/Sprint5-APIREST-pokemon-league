<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PokemonRequest;
use App\Models\Pokemon;
use App\Models\Trainer;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:pokemons.view')->only(['index', 'show', 'available']);
        $this->middleware('permission:pokemons.create')->only(['store']);
        $this->middleware('permission:pokemons.update')->only(['update', 'assignToTrainer', 'releaseFromTrainer']);
        $this->middleware('permission:pokemons.delete')->only(['destroy']);
    }

    /**
     * Display a listing of pokemons with optional filters.
     */
    public function index(Request $request)
    {
        $query = Pokemon::query();
        
        // Apply type filter
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // Apply status filter (available/captured)
        if ($request->has('status')) {
            if ($request->status === 'available') {
                $query->whereNull('trainer_id');
            } elseif ($request->status === 'captured') {
                $query->whereNotNull('trainer_id');
            }
        }
        
        // Apply search by name
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $pokemons = $query->get();

        return response()->json([
            'data' => $pokemons
        ]);
    }

    /**
     * Display a listing of available pokemons (without trainer).
     */
    public function available()
    {
        $pokemons = Pokemon::whereNull('trainer_id')->get();

        return response()->json([
            'data' => $pokemons
        ]);
    }

    /**
     * Store a newly created pokemon in storage.
     */
    public function store(PokemonRequest $request)
    {
        $pokemon = Pokemon::create($request->validated());

        return response()->json([
            'data' => $pokemon
        ], 201);
    }

    /**
     * Display the specified pokemon with its trainer.
     */
    public function show(Pokemon $pokemon)
    {
        $pokemon->load('trainer');

        return response()->json([
            'data' => $pokemon
        ]);
    }

    /**
     * Update the specified pokemon in storage.
     */
    public function update(PokemonRequest $request, Pokemon $pokemon)
    {
        $pokemon->update($request->validated());

        return response()->json([
            'data' => $pokemon
        ]);
    }

    /**
     * Remove the specified pokemon from storage.
     */
    public function destroy(Pokemon $pokemon)
    {
        $pokemon->delete();

        return response()->json([
            'message' => 'Pokemon deleted successfully'
        ]);
    }

    /**
     * Assign pokemon to a trainer.
     */
    public function assignToTrainer(Pokemon $pokemon, Trainer $trainer)
    {
        // Check if the trainer can add more pokemons
        if (!$trainer->canAddMorePokemons()) {
            return response()->json([
                'message' => 'Trainer has reached the maximum number of pokemons'
            ], 400);
        }

        // Check if pokemon is already assigned to another trainer
        if ($pokemon->trainer_id !== null && $pokemon->trainer_id !== $trainer->id) {
            return response()->json([
                'message' => 'Pokemon is already assigned to another trainer'
            ], 400);
        }

        // Assign pokemon to trainer
        $pokemon->trainer()->associate($trainer);
        $pokemon->save();

        return response()->json([
            'message' => 'Pokemon assigned to trainer successfully'
        ]);
    }

    /**
     * Release pokemon from a trainer.
     */
    public function releaseFromTrainer(Pokemon $pokemon, Trainer $trainer)
    {
        // Check if pokemon is assigned to the specified trainer
        if ($pokemon->trainer_id !== $trainer->id) {
            return response()->json([
                'message' => 'Pokemon is not assigned to this trainer'
            ], 400);
        }

        // Release pokemon
        $pokemon->trainer()->dissociate();
        $pokemon->save();

        return response()->json([
            'message' => 'Pokemon released successfully'
        ]);
    }
}