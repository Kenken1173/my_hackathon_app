<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;

// ユーザーの新しい目標・現状・期日入力画面
class NewgoalController extends Controller
{
    private static function loadDummies($size)
    {
        $result = array();
        for ($i = 0; $i < $size; $i++) {
            $dummies = json_decode(file_get_contents(storage_path("dummy_milestones")));
            $dummies_count = count($dummies);
            array_push($result, $dummies[rand(0, $dummies_count - 1)]);
        }
        return $result;
    }
    public function index()
    {
        $user = auth()->user();
        return view("newGoal", ["username" => $user->name]);
    }
    public function new(Request $request)
    {
        $user = auth()->user();
        // DD($resuest->input());
        DD($request);
        $goal = Goal::create([
            "user_id" => $user->id,
            "name" => $request->goalTitle,
            "category" => $request->category
        ]);
        $milestones = $this::loadDummies(rand(1, 5));
        foreach ($milestones as $milestone) {
            Milestone::create([
                "goal_id" => $goal->id,
            ]);
        }
        return redirect("/");
    }
}