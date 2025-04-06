<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Battle>
 */
class BattleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trainer1 = Trainer::factory()->create();
        $trainer2 = Trainer::factory()->create();
        $winner = fake()->boolean() ? $trainer1->id : $trainer2->id;
        
        return [
            'trainer1_id' => $trainer1->id,
            'trainer2_id' => $trainer2->id,
            'winner_id' => fake()->boolean(80) ? $winner : null, // 80% chance of having a winner, 20% chance of a draw
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the battle is a draw.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draw()
    {
        return $this->state(function (array $attributes) {
            return [
                'winner_id' => null,
            ];
        });
    }
}