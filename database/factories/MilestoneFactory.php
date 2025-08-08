<?php

namespace Database\Factories;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        $startDate = CarbonImmutable::today()->addDays(rand(0, 60));
        return [
            "name" => fake()->word(),
            "description" => fake()->paragraph(1),
            "startDate" => $startDate,
            "endDate" => $startDate->addDays(rand(1, 4)),
            "achieved" => fake()->boolean(),
            "goal_id" => fake()->numberBetween(1,20)
        ];
    }
}
