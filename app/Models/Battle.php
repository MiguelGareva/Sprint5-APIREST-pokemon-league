<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trainer1_id',
        'trainer2_id',
        'winner_id',
        'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the trainer1 that participates in the battle.
     */
    public function trainer1()
    {
        return $this->belongsTo(Trainer::class, 'trainer1_id');
    }

    /**
     * Get the trainer2 that participates in the battle.
     */
    public function trainer2()
    {
        return $this->belongsTo(Trainer::class, 'trainer2_id');
    }

    /**
     * Get the winner of the battle.
     */
    public function winner()
    {
        return $this->belongsTo(Trainer::class, 'winner_id');
    }
}