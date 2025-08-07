<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Milestone>
 */
class MilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category" => ["a", "b", "c", "d"][rand(0, 3)],
            "name" => fake()->word(),
            "description" => fake()->paragraph(),
            "startDate" => fake()->date(),
            "endDate" => fake()->date(),
            "achieved" => fake()->boolean(),
            "goal_id" => fake()->numberBetween(1,10)
        ];
    }
}
