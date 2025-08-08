<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ユーザーの新しい目標・現状・期日入力画面
class NewgoalController extends Controller
{
    private function generateMilestones($goalTitle, $category, $deadline)
    {
        $milestones = [];
        $startDate = Carbon::now();
        $endDate = Carbon::parse($deadline);
        
        $categoryMilestones = [
            'study' => [
                '基礎知識の習得',
                '実践的な学習',
                '模擬試験・演習',
                '最終準備と復習'
            ],
            'health' => [
                '運動習慣の確立',
                '栄養管理の改善',
                '目標設定の見直し',
                '最終目標達成'
            ],
            'work' => [
                'スキル習得計画',
                '実務での実践',
                '成果の評価',
                '目標達成・次のステップ'
            ],
            'hobby' => [
                '基本技術の習得',
                '応用技術の学習',
                '作品制作・実践',
                '目標達成・発表'
            ]
        ];

        $milestoneNames = $categoryMilestones[$category] ?? [
            '第1段階: 準備',
            '第2段階: 実行',
            '第3段階: 向上',
            '第4段階: 完成'
        ];

        $totalDays = $startDate->diffInDays($endDate);
        $milestoneCount = count($milestoneNames);
        $daysPerMilestone = max(1, intval($totalDays / $milestoneCount));

        for ($i = 0; $i < $milestoneCount; $i++) {
            $milestoneStart = $startDate->copy()->addDays($i * $daysPerMilestone);
            $milestoneEnd = $i == $milestoneCount - 1 
                ? $endDate->copy() 
                : $startDate->copy()->addDays(($i + 1) * $daysPerMilestone - 1);

            $milestones[] = [
                'name' => $milestoneNames[$i],
                'description' => "「{$goalTitle}」達成のための{$milestoneNames[$i]}を行います。",
                'startDate' => $milestoneStart->format('Y-m-d H:i:s'),
                'endDate' => $milestoneEnd->format('Y-m-d H:i:s'),
                'achieved' => false
            ];
        }

        return $milestones;
    }

    public function index()
    {
        $user = auth()->user();
        return view("newGoal", ["username" => $user->name]);
    }
    
    public function new(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'goalTitle' => 'required|string|max:255',
            'category' => 'required|string',
            'goalDeadline' => 'required|date|after:today'
        ]);

        $goal = Goal::create([
            "user_id" => $user->id,
            "name" => $request->goalTitle,
            "category" => $request->category
        ]);

        $milestones = $this->generateMilestones(
            $request->goalTitle,
            $request->category,
            $request->goalDeadline
        );

        foreach ($milestones as $milestone) {
            Milestone::create([
                "goal_id" => $goal->id,
                "name" => $milestone['name'],
                "description" => $milestone['description'],
                "startDate" => $milestone['startDate'],
                "endDate" => $milestone['endDate'],
                "achieved" => $milestone['achieved']
            ]);
        }

        return redirect("/");
    }
}