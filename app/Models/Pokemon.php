<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pokemons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'level',
        'stats',
        'trainer_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stats' => 'json',
    ];

    /**
     * Get the trainer that owns the pokemon.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Override the save method to check trainer's pokemon limit.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // If trainer_id is being changed (pokemon is being assigned to a trainer)
        if ($this->isDirty('trainer_id') && $this->trainer_id !== null) {
            $trainer = Trainer::find($this->trainer_id);
            
            // Check if trainer can add more pokemons
            if ($trainer && !$trainer->canAddMorePokemons()) {
                return false;
            }
        }
        
        return parent::save($options);
    }
}