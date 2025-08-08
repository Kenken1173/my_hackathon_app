<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\GoalSeeder;
use Database\Seeders\MilestoneSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 既知のログイン用ユーザー
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            // パスワードは UserFactory のデフォルト 'password'
        ]);
        // 追加のダミーユーザー
        User::factory(3)->create();
        $this->call([
            GoalSeeder::class,
            MilestoneSeeder::class,
        ]);
    }
}
