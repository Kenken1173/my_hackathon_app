<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;

// ゴールを選択し、マイルストーンのWBS風の表を表示する画面
class MileStoneTableController extends Controller
{
    public function get($goal_id)
    {
        $milestones = Milestone::where("goal_id", $goal_id)->get();
        return view("milestoneTable", ["goal" => $milestones]);
    }
}