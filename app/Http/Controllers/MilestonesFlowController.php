<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestonesFlowController extends Controller
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

        $milestones = Milestone::where('goal_id', $goal->id)->get();

        return view('milestonesFlow', [
            'goal' => [
                'name' => $goal->name,
                'milestones' => $milestones->map(function ($m) {
                    return [
                        'name' => $m->name,
                        'description' => $m->description,
                        'startDate' => $m->startDate,
                        'endDate' => $m->endDate,
                        'achieved' => (bool) $m->achieved,
                    ];
                })->toArray(),
                'id' => $goal->id,
            ],
            'milestones' => $milestones,
            'username' => $user->name,
        ]);
    }
}
