<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pokemon>
 */
class PokemonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Fire', 'Water', 'Grass', 'Electric', 'Psychic', 'Normal', 'Fighting', 'Flying', 'Poison', 'Ground', 'Rock', 'Bug', 'Ghost', 'Steel', 'Ice', 'Dragon', 'Dark', 'Fairy'];
        
        return [
            'name' => fake()->unique()->word(),
            'type' => fake()->randomElement($types),
            'level' => fake()->numberBetween(1, 100),
            'stats' => json_encode([
                'hp' => fake()->numberBetween(1, 255),
                'attack' => fake()->numberBetween(1, 255),
                'defense' => fake()->numberBetween(1, 255),
                'special_attack' => fake()->numberBetween(1, 255),
                'special_defense' => fake()->numberBetween(1, 255),
                'speed' => fake()->numberBetween(1, 255),
            ]),
            'trainer_id' => null,
        ];
    }

    /**
     * Indicate that the Pokemon has a trainer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withTrainer()
    {
        return $this->state(function (array $attributes) {
            return [
                'trainer_id' => Trainer::factory(),
            ];
        });
    }
}