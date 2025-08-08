<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MilestonesFlowController extends Controller
{
    public function get($goal_id)
    {
        $user = User::find(2); // TODO 定数ではなくする
        // $goal = Goal::where("user_id", $user->id)->where("id", $goal_id)->first(); // こっちのほうが適切
        $goal = Goal::where("id", $goal_id)->first();
        $milestones = Milestone::where("goal_id", $goal->id)->get();
        // DD($goal);
        // if (!$goal) {
        //     return view('welcome');
        // }
        
        return view('milestonesFlow', [
            'goal' => $goal,
            'milestones' => $milestones,
            "username" => $user->name
        ]);
    }
}
