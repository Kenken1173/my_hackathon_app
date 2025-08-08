<?php

namespace Database\Seeders;

use App\Models\Milestone;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table("milestones")->truncate();

        $goals = Goal::all();
        foreach ($goals as $goal) {
            $numMilestones = rand(3, 8);
            $cursor = Carbon::today()->addDays(rand(-10, 10));
            for ($i = 0; $i < $numMilestones; $i++) {
                $duration = rand(1, 7);
                $gap = rand(0, 5);

                $start = $cursor->copy();
                $end = $start->copy()->addDays($duration);

                Milestone::create([
                    'name' => fake()->word(),
                    'description' => fake()->sentence(10),
                    'startDate' => $start,
                    'endDate' => $end,
                    'achieved' => (bool)rand(0,1),
                    'goal_id' => $goal->id,
                ]);

                $cursor = $end->copy()->addDays($gap);
            }
        }
    }
}
