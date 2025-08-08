<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Milestone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

class MilestonesWBSController extends Controller
{
    public function get($goal_id)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $goal = Goal::where('id', $goal_id)->where('user_id', $user->id)->first();
        if (! $goal) {
            abort(404);
        }
        $milestones = Milestone::where('goal_id', $goal->id)
            ->orderBy('startDate')
            ->get();

        $startDate = $milestones->isNotEmpty() ? Carbon::parse($milestones->min('startDate')) : null;
        $endDate = $milestones->isNotEmpty() ? Carbon::parse($milestones->max('endDate')) : null;
        return view("milestonesWBS", [
            "goal" => $goal,
            "milestones" => $milestones,
            "username" => $user->name,
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
    }
}