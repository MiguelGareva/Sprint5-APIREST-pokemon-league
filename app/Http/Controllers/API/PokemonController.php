<?php

namespace App\Http\Controllers\API;

use App\Exceptions\PokemonAssignmentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PokemonRequest;
use App\Models\Pokemon;
use App\Models\Trainer;
use App\Services\PokemonAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PokemonController extends Controller
{
    /**
     * The pokemon assignment service instance.
     *
     * @var \App\Services\PokemonAssignmentService
     */
    protected $pokemonAssignmentService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\PokemonAssignmentService $pokemonAssignmentService
     * @return void
     */
    public function __construct(PokemonAssignmentService $pokemonAssignmentService)
    {
        $this->pokemonAssignmentService = $pokemonAssignmentService;
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
        $pokemons = $this->pokemonAssignmentService->getAvailablePokemons();

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
        try {
            $this->pokemonAssignmentService->assignPokemon($pokemon, $trainer);
            
            return response()->json([
                'message' => 'Pokemon assigned to trainer successfully'
            ]);
        } catch (PokemonAssignmentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to assign pokemon: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to assign pokemon'
            ], 500);
        }
    }

    /**
     * Release pokemon from a trainer.
     */
    public function releaseFromTrainer(Pokemon $pokemon, Trainer $trainer)
    {
        try {
            // Check if pokemon is assigned to the specified trainer
            if ($pokemon->trainer_id !== $trainer->id) {
                return response()->json([
                    'message' => 'Pokemon is not assigned to this trainer'
                ], 400);
            }
            
            $this->pokemonAssignmentService->releasePokemon($pokemon);
            
            return response()->json([
                'message' => 'Pokemon released successfully'
            ]);
        } catch (PokemonAssignmentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to release pokemon: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to release pokemon'
            ], 500);
        }
    }

    /**
     * Transfer pokemon from one trainer to another.
     */
    public function transferPokemon(Pokemon $pokemon, Trainer $newTrainer)
    {
        try {
            // Use the new service method
            $this->pokemonAssignmentService->transferPokemon($pokemon, $newTrainer);
            
            return response()->json([
                'message' => 'Pokemon transferred successfully'
            ]);
        } catch (PokemonAssignmentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to transfer pokemon: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to transfer pokemon'
            ], 500);
        }
    }
}