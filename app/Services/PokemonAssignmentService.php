<?php

namespace App\Services;

use App\Exceptions\PokemonAssignmentException;
use App\Models\Pokemon;
use App\Models\Trainer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PokemonAssignmentService
{
    /**
     * Assign a pokemon to a trainer
     *
     * @param Pokemon $pokemon The pokemon to assign
     * @param Trainer $trainer The trainer to assign the pokemon to
     * @return bool True if the pokemon was assigned, false otherwise
     * @throws PokemonAssignmentException If the trainer has reached the maximum number of pokemons
     */
    public function assignPokemon(Pokemon $pokemon, Trainer $trainer): bool
    {
        // Check if the trainer can have more pokemons
        if (!$trainer->canAddMorePokemons()) {
            throw new PokemonAssignmentException(
                "Trainer has reached the maximum number of pokemons",
                PokemonAssignmentException::ERROR_TRAINER_MAX_POKEMON_REACHED
            );
        }

        // Check if the pokemon is already assigned to another trainer
        if ($pokemon->trainer_id !== null) {
            throw new PokemonAssignmentException(
                "Pokemon is already assigned to a trainer",
                PokemonAssignmentException::ERROR_POKEMON_ALREADY_ASSIGNED
            );
        }

        try {
            // Assign the pokemon to the trainer
            $pokemon->trainer()->associate($trainer);
            return $pokemon->save();
        } catch (\Exception $e) {
            throw new PokemonAssignmentException(
                "Failed to assign pokemon: " . $e->getMessage()
            );
        }
    }

    /**
     * Release a pokemon from its trainer
     *
     * @param Pokemon $pokemon The pokemon to release
     * @return bool True if the pokemon was released, false otherwise
     * @throws PokemonAssignmentException If the pokemon doesn't belong to a trainer
     */
    public function releasePokemon(Pokemon $pokemon): bool
    {
        // Check if the pokemon belongs to a trainer
        if ($pokemon->trainer_id === null) {
            throw new PokemonAssignmentException(
                "Pokemon doesn't belong to a trainer",
                PokemonAssignmentException::ERROR_POKEMON_NOT_ASSIGNED
            );
        }

        try {
            // Release the pokemon
            $pokemon->trainer()->dissociate();
            return $pokemon->save();
        } catch (\Exception $e) {
            throw new PokemonAssignmentException(
                "Failed to release pokemon: " . $e->getMessage()
            );
        }
    }

    /**
     * Transfer a pokemon from one trainer to another
     *
     * @param Pokemon $pokemon The pokemon to transfer
     * @param Trainer $newTrainer The trainer to transfer the pokemon to
     * @return bool True if the pokemon was transferred, false otherwise
     * @throws PokemonAssignmentException If the pokemon doesn't belong to a trainer
     * or if the new trainer has reached the maximum number of pokemons
     */
    public function transferPokemon(Pokemon $pokemon, Trainer $newTrainer): bool
    {
        // Check if the pokemon belongs to a trainer
        if ($pokemon->trainer_id === null) {
            throw new PokemonAssignmentException(
                "Pokemon doesn't belong to a trainer",
                PokemonAssignmentException::ERROR_POKEMON_NOT_ASSIGNED
            );
        }

        // Check if the new trainer can have more pokemons
        if (!$newTrainer->canAddMorePokemons()) {
            throw new PokemonAssignmentException(
                "New trainer has reached the maximum number of pokemons",
                PokemonAssignmentException::ERROR_TRAINER_MAX_POKEMON_REACHED
            );
        }

        try {
            DB::beginTransaction();
            
            // Release the pokemon from its current trainer
            $pokemon->trainer()->dissociate();
            $pokemon->save();
            
            // Assign the pokemon to the new trainer
            $pokemon->trainer()->associate($newTrainer);
            $result = $pokemon->save();
            
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new PokemonAssignmentException(
                "Failed to transfer pokemon: " . $e->getMessage()
            );
        }
    }

    /**
     * Get all available pokemons (not assigned to any trainer)
     *
     * @return Collection Collection of available pokemons
     */
    public function getAvailablePokemons(): Collection
    {
        return Pokemon::whereNull('trainer_id')->get();
    }

    /**
     * Get all pokemons of a trainer
     *
     * @param Trainer $trainer The trainer to get the pokemons from
     * @return Collection Collection of trainer's pokemons
     */
    public function getTrainerPokemons(Trainer $trainer): Collection
    {
        return $trainer->pokemons;
    }
}