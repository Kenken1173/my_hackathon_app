<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MilestonesFlowController extends Controller
{
    public function get($goal_id)
    {
        $dataStorePath = storage_path('dataStore.json');
        
        if (!file_exists($dataStorePath)) {
            return view('welcome');
        }
        
        $jsonData = file_get_contents($dataStorePath);
        $data = json_decode($jsonData, true);
        
        $goal = null;
        $username = null;
        
        // Find the goal by ID
        foreach ($data['goals'] as $goalItem) {
            if ($goalItem['id'] == $goal_id) {
                $goal = $goalItem;
                // Find the user for this goal
                foreach ($data['users'] as $user) {
                    if ($user['id'] == $goalItem['userId']) {
                        $username = $user['name'];
                        break;
                    }
                }
                break;
            }
        }
        
        if (!$goal) {
            return view('welcome');
        }
        
        return view('milestonesFlow', [
            'goal' => $goal,
            'username' => $username
        ]);
    }
}
