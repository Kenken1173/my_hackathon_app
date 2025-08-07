<x-layout :username=$username title="goal list">
    <!-- ウェルカムセクション -->
    <div class="welcome-section relative">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">こんにちは！</h2>
                    <p class="text-sm text-gray-600">今日も目標に向けて進んでいきましょう</p>
                </div>
                <div class="text-3xl">🎯</div>
            </div>

            {{-- <!-- 今日のサマリー -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-white/50">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">今日のタスク</span>
                    <span class="font-semibold text-primary-600">2件完了</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div class="bg-primary-500 h-1.5 rounded-full" style="width: 75%"></div>
                </div>
            </div> --}}
        </div>
    </div>

    {{-- <!-- フィルター -->
    <div class="flex items-center space-x-2 mb-6 overflow-x-auto">
        <button
            class="filter-btn active px-4 py-2 bg-primary-500 text-white text-sm font-semibold rounded-full whitespace-nowrap transition-colors"
            data-filter="all">
            すべて
        </button>
        <button
            class="filter-btn px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-full whitespace-nowrap hover:bg-gray-200 transition-colors"
            data-filter="active">
            進行中
        </button>
        <button
            class="filter-btn px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-full whitespace-nowrap hover:bg-gray-200 transition-colors"
            data-filter="completed">
            完了済み
        </button>
        <button
            class="filter-btn px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-full whitespace-nowrap hover:bg-gray-200 transition-colors"
            data-filter="overdue">
            期限切れ
        </button>
    </div> --}}

    <!-- 目標リスト -->
    <div class="space-y-4" id="goalsList">
        @php
            // var_dump($goals[0]);
            // echo $goals->name;
        @endphp
        @foreach ($goals_with_milestone as $goal_with_count)
            <!-- アクティブな目標1 -->
            <div class="goal-card bg-white rounded-xl p-4 shadow-sm border border-gray-100" data-status="active"
                onclick="window.location.href='milestone-flow.html'">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <span class="category-badge category-study mr-2">学習</span>
                            <span class="text-xs text-gray-500">あと 28日 </span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">{{  $goal_with_count[0]->name }}</h3>
                        <!-- checkしてほしい -->
                        <p class="text-sm text-gray-600 mb-3">期限：#TODO 期限</p>
                        {{-- <p class="text-sm text-gray-600 mb-3">期限：{{ end($goal["milestones"])["endDate"] }}</p> --}}
                    </div>
                    <!-- 進捗リング -->
                    <div class="relative w-16 h-16 ml-4">
                        <svg class="progress-ring w-16 h-16">
                            <circle cx="32" cy="32" r="28" stroke="#E5E7EB" stroke-width="4" fill="none" />
                            @if ($goal_with_count[2] == 0 || $goal_with_count[1] == 0)
                            <circle class="progress-ring-circle" cx="32" cy="32" r="28" stroke="#22C55E" stroke-width="4"
                            fill="none" stroke-dasharray="{{ 0 }}, 1000" />
                            @else
                            <circle class="progress-ring-circle" cx="32" cy="32" r="28" stroke="#22C55E" stroke-width="4"
                            fill="none" stroke-dasharray="{{ $goal_with_count[2] / $goal_with_count[1] * 180}}, 1000"
                            />
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            @if ($goal_with_count[2] == 0 || $goal_with_count[1] == 0)
                            <span class="text-xs font-bold text-gray-700">0%</span>
                                
                            @else
                                <span class="text-xs font-bold text-gray-700">{{ $goal_with_count[2] / $goal_with_count[1] * 100 }}%</span>
                            @endif
                            {{-- <span class="text-xs font-bold text-gray-700">{{ 50 }}%</span> --}}
                            {{-- <span class="text-xs font-bold text-gray-700">{{ floor($goal_with_count[2]/
                                $goal_with_count[1]*100) }}%</span> --}}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-primary-600">3</span>
                        </div>
                        <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-secondary-600">1</span>
                        </div>
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-gray-500">2</span>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">{{ $goal_with_count[2] }}タスク中 {{ $goal_with_count[1] }} 完了</div>
                </div>
            </div>
        @endforeach
        {{-- <!-- 完了済みの目標 -->
        <div class="goal-card bg-white rounded-xl p-4 shadow-sm border border-gray-100" data-status="completed">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center mb-2">
                        <span class="category-badge category-health mr-2">健康</span>
                        <span class="text-xs text-primary-600 font-semibold">完了</span>
                    </div>
                    <h3 class="font-bold text-gray-700 text-lg mb-1 truncate">10kg減量する</h3>
                    <p class="text-sm text-gray-500 mb-3">完了日：2024年10月15日</p>
                </div>

                <!-- 完了チェック -->
                <div class="w-16 h-16 ml-4 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-primary-600">全8タスク完了</span>
                </div>
                <div class="text-xs text-gray-500">3ヶ月で達成</div>
            </div>
        </div>

        <!-- 期限切れの目標 -->
        <div class="goal-card bg-white rounded-xl p-4 shadow-sm border border-orange-200" data-status="overdue">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center mb-2">
                        <span class="category-badge category-work mr-2">仕事</span>
                        <span
                            class="text-xs text-warning-600 font-semibold bg-warning-100 px-2 py-1 rounded">期限切れ</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">プレゼン資料作成</h3>
                    <p class="text-sm text-warning-600 mb-3">期限：2024年11月01日（3日遅れ）</p>
                </div>

                <!-- 警告アイコン -->
                <div class="w-16 h-16 ml-4 bg-warning-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-semibold text-primary-600">4</span>
                    </div>
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-semibold text-gray-500">1</span>
                    </div>
                </div>
                <button class="text-xs text-warning-600 hover:text-warning-700 font-semibold underline">
                    期限を延長
                </button>
            </div>
        </div>
    </div> --}}

    <!-- 空の状態（目標がない場合） -->
    <div class="empty-state hidden" id="emptyState">
        <div class="empty-state-icon">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">目標を設定しましょう</h3>
        <p class="text-gray-600 mb-6">新しい目標を作成して、達成への道筋を描きましょう。</p>
        <button
            class="bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200"
            onclick="window.location.href='goal-input.html'">
            最初の目標を作成
        </button>
    </div>

    <!-- 統計サマリー -->
    <div class="bg-white rounded-xl p-4 mt-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            今月の実績
        </h3>

        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600 mb-1">1</div>
                <div class="text-xs text-gray-600">達成した目標</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-secondary-600 mb-1">12</div>
                <div class="text-xs text-gray-600">完了したタスク</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-accent-600 mb-1">89</div>
                <div class="text-xs text-gray-600">連続日数</div>
            </div>
        </div>
    </div>
    </div>
</x-layout>