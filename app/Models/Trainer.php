<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'points',
    ];

    /**
     * Maximum number of pokemons a trainer can have.
     */
    public const MAX_POKEMONS = 3;

    /**
     * Get the user that owns the trainer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pokemons for the trainer.
     */
    public function pokemons()
    {
        return $this->hasMany(Pokemon::class);
    }

    /**
     * Get the battles where the trainer is trainer1.
     */
    public function battlesAsTrainer1()
    {
        return $this->hasMany(Battle::class, 'trainer1_id');
    }

    /**
     * Get the battles where the trainer is trainer2.
     */
    public function battlesAsTrainer2()
    {
        return $this->hasMany(Battle::class, 'trainer2_id');
    }

    /**
     * Get the battles won by the trainer.
     */
    public function battlesWon()
    {
        return $this->hasMany(Battle::class, 'winner_id');
    }

    /**
     * Get all battles where the trainer participated.
     */
    public function battles()
    {
        return $this->battlesAsTrainer1->concat($this->battlesAsTrainer2);
    }
    
    /**
     * Check if the trainer can add more pokemons.
     *
     * @return bool
     */
    public function canAddMorePokemons(): bool
    {
        return $this->pokemons()->count() < self::MAX_POKEMONS;
    }
    
    /**
     * Add a pokemon to the trainer if possible.
     *
     * @param \App\Models\Pokemon $pokemon
     * @return bool
     */
    public function addPokemon(Pokemon $pokemon): bool
    {
        if (!$this->canAddMorePokemons()) {
            return false;
        }
        
        $pokemon->trainer()->associate($this);
        $pokemon->save();
        
        return true;
    }
}