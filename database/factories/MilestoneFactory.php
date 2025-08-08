<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $startDate = Carbon::today()->addDays(rand(1, 60));
        return [
            "name" => fake()->word(),
            "description" => fake()->paragraph(),
            "startDate" => $startDate,
            "endDate" => $startDate,
            "achieved" => fake()->boolean(),
            "goal_id" => fake()->numberBetween(1,20)
        ];
    }
}
