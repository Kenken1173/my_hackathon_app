@php
    use Carbon\Carbon;
    
    $milestones;
    $startDate = Carbon::parse($milestones->first()->startDate);
    $endDate = Carbon::parse($milestones->last()->endDate);
    
    $totalDays = $startDate->diffInDays($endDate) ? $startDate->diffInDays($endDate) : 1;
    $today = Carbon::today();
    // DD($startDate, $endDate, $totalDays);
    $todayPosition = $startDate ? max(0, min(100, ($startDate->diffInDays($today) / $totalDays) * 100)) : 50;
    
    $completedCount = $milestones->where('achieved', true)->count();
    $totalCount = count($milestones);
    $progressPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
    
    function generateMonthHeaders($startDate, $endDate) {
        if (!$startDate || !$endDate) return [];
        
        $months = [];
        $current = $startDate->copy()->startOfMonth();
        $end = $endDate->copy()->endOfMonth();
        
        while ($current->lte($end)) {
            $months[] = [
                'name' => $current->format('n月'),
                'year' => $current->format('Y年')
            ];
            $current->addMonth();
        }
        
        return $months;
    }
    
    function calculateTimelinePosition($start, $end, $projectStart, $totalDays) {
        if (!$projectStart || $totalDays <= 0) return ['left' => 0, 'width' => 0];
        
        $startDays = $projectStart->diffInDays(Carbon::parse($start));
        $durationDays = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
        
        $leftPercent = max(0, ($startDays / $totalDays) * 100);
        $widthPercent = min(100 - $leftPercent, ($durationDays / $totalDays) * 100);
        
        return ['left' => $leftPercent, 'width' => $widthPercent];
    }
    
    $monthHeaders = generateMonthHeaders($startDate, $endDate);
@endphp

<x-layout :username=$username title="wbs">
     <style>
            .timeline-bar {
                position: relative;
                height: 24px;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .timeline-bar:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .timeline-progress {
                height: 100%;
                border-radius: 12px;
                transition: all 0.3s ease;
                position: absolute;
                top: 0;
            }
            .current-marker {
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            /* タイムライン専用スタイル */
            .timeline-container {
                overflow-x: auto;
                overflow-y: hidden;
                scrollbar-width: thin;
                scrollbar-color: #CBD5E0 #F7FAFC;
                width: 100%;
                position: relative;
            }

            .timeline-container::-webkit-scrollbar {
                height: 6px;
            }

            .timeline-container::-webkit-scrollbar-track {
                background: #F7FAFC;
                border-radius: 3px;
            }

            .timeline-container::-webkit-scrollbar-thumb {
                background: #CBD5E0;
                border-radius: 3px;
            }

            .timeline-container::-webkit-scrollbar-thumb:hover {
                background: #A0AEC0;
            }

            .timeline-content {
                min-width: 600px;
                width: max-content;
                position: relative;
            }

            @media (min-width: 768px) {
                .timeline-content {
                    min-width: 800px;
                }
            }

            @media (min-width: 1024px) {
                .timeline-content {
                    min-width: 1000px;
                }
            }

            .month-header {
                position: sticky;
                top: 0;
                background: white;
                z-index: 10;
                border-bottom: 1px solid #E2E8F0;
                padding: 8px 0;
                width: 100%;
            }

            .current-date-marker {
                position: absolute;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #EF4444;
                z-index: 5;
                opacity: 0.8;
                left: 58%;
            }

            .current-date-marker::before {
                content: '今日';
                position: absolute;
                top: -20px;
                left: -12px;
                background: #EF4444;
                color: white;
                font-size: 10px;
                padding: 2px 4px;
                border-radius: 4px;
                white-space: nowrap;
            }

            .timeline-row:hover {
                background-color: #F8FAFC;
            }

            .task-name {
                cursor: pointer;
                transition: all 0.2s ease;
                border-radius: 6px;
                padding: 4px 6px;
                margin: -4px -6px;
            }

            .task-name:hover {
                background-color: #F1F5F9;
                color: #1E40AF;
            }



            .tap-hint {
                animation: bounce 2s infinite;
                opacity: 0.7;
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-4px); }
                60% { transform: translateY(-2px); }
            }
        </style>

       
    <!-- 目標タイトル -->
    <div class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $goal["name"] }}</h2>
        @if($endDate)
        <p class="text-sm text-gray-500">期限{{ $endDate->format('Y年m月d日') }}</p>
        @endif
    </div>

    <!-- 進捗サマリー -->
    <div class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-gray-900">進捗状況</span>
            <span class="text-sm font-semibold text-primary-600">{{ $completedCount }}/{{ $totalCount }} 完了（{{ $progressPercentage }}%）</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-primary-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
        </div>
    </div>
    <!-- タイムラインチャート -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="p-4 pb-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                タイムライン
            </h3>
            <div class="flex items-center text-xs text-gray-500">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                横スクロール可能
            </div>
        </div>
    </div>
    
    <!-- 固定部分とスクロール部分を分離 -->
    <div class="flex">
        <!-- 左側固定部分（タスク名） -->
        <div class="flex-shrink-0 bg-gray-50 border-r border-gray-200" style="width: 120px;">
            <!-- 月ヘッダー用の空白 -->
            <div class="h-12 border-b border-gray-200 px-3 py-2">
                <div class="text-xs font-medium text-gray-600 text-center">タスク</div>
            </div>
            
            <!-- タスクリスト -->
            <div class="px-3 py-8">
                @if(count($milestones) > 0)
                    @foreach($milestones as $index => $milestone)
                    @php
                        $isAchieved = $milestone['achieved'] ?? false;
                        $start = Carbon::parse($milestone['startDate']);
                        $end = Carbon::parse($milestone['endDate']);
                        $isInProgress = !$isAchieved && $today->between($start, $end);
                        $isFuture = $today->lt($start);
                        $textColor = $isAchieved ? 'text-primary-700' : ($isInProgress ? 'text-secondary-700' : 'text-gray-500');
                        $period = $start->diffInDays($end) + 1;
                        $onclick = "showTaskDetail(" .
                            json_encode($milestone->name) . "," .
                            json_encode($milestone->description ?? '') . "," .
                            json_encode($milestone->startDate) . "," .
                            json_encode($milestone->endDate) . "," .
                            json_encode($isAchieved ? 'completed' : ($isInProgress ? 'in-progress' : 'pending')) . "," .
                            json_encode($period) .
                        ")";
                    @endphp
                    <div id="task-{{ $index }}" class="py-2 flex items-center">
                        <div class="task-name text-xs {{ $textColor }} font-medium leading-tight" onclick="{{ $onclick }}">
                            {{ $milestone['name'] }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="py-2 flex items-center justify-center">
                        <div class="text-xs text-gray-400">マイルストーンなし</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 右側スクロール部分（WBSタイムライン） -->
        <div class="timeline-container flex-1 relative">
            <div class="timeline-content">
                <!-- 月ヘッダー -->
                <div class="month-header h-12 px-4 py-2">
                    @if(count($monthHeaders) > 0)
                    <div class="flex text-xs text-gray-600 text-center font-medium">
                        @foreach($monthHeaders as $month)
                        <div class="flex-1 px-2">{{ $month['name'] }}</div>
                        @endforeach
                    </div>
                    <div class="flex mt-1">
                        @foreach($monthHeaders as $index => $month)
                        <div class="flex-1 {{ $index < count($monthHeaders) - 1 ? 'border-r border-gray-200' : '' }} h-2"></div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-xs text-gray-500">期間情報なし</div>
                    @endif
                </div>

                <!-- 現在日マーカー -->
                <div class="current-date-marker" style="left: {{ $todayPosition }}%"></div>

                <!-- タイムラインバー -->
                <div class="px-4 py-8">
                    @if(count($milestones) > 0)
                        @foreach($milestones as $index => $milestone2)
                        @php
                            $position = calculateTimelinePosition($milestone2['startDate'], $milestone2['endDate'], $milestone2->startDate, $totalDays);
                            
                            $isAchieved = $milestone2->achieved ?? false;
                            $start = Carbon::parse($milestone2->startDate);
                            $end = Carbon::parse($milestone2->endDate);
                            $isInProgress = !$isAchieved && $today->between($start, $end);

                            $isFuture = $today->lt($start);
                            $textColor = $isAchieved ? 'text-primary-700' : ($isInProgress ? 'text-secondary-700' : 'text-gray-500');
                            $period = $start->diffInDays($end) + 1;
                            $onclick2 = "showTaskDetail(" .
                                json_encode($milestone2->name) . "," .
                                json_encode($milestone2->description ?? '') . "," .
                                json_encode($milestone2->startDate) . "," .
                                json_encode($milestone2->endDate) . "," .
                                json_encode($isAchieved ? 'completed' : ($isInProgress ? 'in-progress' : 'pending')) . "," .
                                json_encode($period) .
                            ")";
                            $bgColor2 = $isAchieved ? 'bg-primary-500' : ($isInProgress ? 'bg-secondary-500' : 'bg-gray-300');
                            $currentMarker = $isInProgress ? 'current-marker' : '';
                        @endphp
                        <div id="timeline-{{ $index }}" class="timeline-row py-2 flex items-center">
                            <div class="timeline-bar bg-gray-200 relative w-full" onclick="{{ $onclick2 }}">
                                <div class="timeline-progress {{ $bgColor2 }} {{ $currentMarker }}" 
                                     style="width: {{ $position['width'] }}%; left: {{ $position['left'] }}%;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="py-2 flex items-center justify-center">
                            <div class="text-xs text-gray-400">マイルストーンデータなし</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 凡例 -->
    <div class="flex justify-center space-x-4 mt-4 mb-4 text-xs">
        <div class="flex items-center">
            <div class="w-3 h-3 bg-primary-500 rounded mr-1"></div>
            <span class="text-gray-600">完了</span>
        </div>
        <div class="flex items-center">
            <div class="w-3 h-3 bg-secondary-500 rounded mr-1"></div>
            <span class="text-gray-600">進行中</span>
        </div>
        <div class="flex items-center">
            <div class="w-3 h-3 bg-gray-300 rounded mr-1"></div>
            <span class="text-gray-600">未着手</span>
        </div>
    </div>

    </div>
    
    <script>
        function syncRowHeights() {
            // ページ読み込み後に高さを同期
            const taskRows = document.querySelectorAll('[id^="task-"]');
            const timelineRows = document.querySelectorAll('[id^="timeline-"]');
            
            taskRows.forEach((taskRow, index) => {
                const timelineRow = timelineRows[index];
                if (timelineRow) {
                    // 両方の高さをリセット
                    taskRow.style.height = 'auto';
                    timelineRow.style.height = 'auto';
                    
                    // より高い方の高さを取得
                    const taskHeight = taskRow.offsetHeight;
                    const timelineHeight = timelineRow.offsetHeight;
                    const maxHeight = Math.max(taskHeight, timelineHeight) + 'px';
                    
                    // 両方に同じ高さを設定
                    taskRow.style.height = maxHeight;
                    timelineRow.style.height = maxHeight;
                }
            });
        }
        
        // ページ読み込み完了後に実行
        document.addEventListener('DOMContentLoaded', syncRowHeights);
        
        // リサイズ時にも実行
        window.addEventListener('resize', syncRowHeights);

document.addEventListener('DOMContentLoaded', function() {

    const timelineContainer = document.querySelector('.timeline-container');
    const currentMarker = document.querySelector('.current-date-marker');

    if (timelineContainer && currentMarker) {
        setTimeout(() => {
            const containerWidth = timelineContainer.clientWidth;
            const contentWidth = timelineContainer.scrollWidth;

            // 現在日マーカーの実際の位置を取得（{{ $todayPosition }}%をJavaScriptで使用）
            const todayPositionPercent = {{ $todayPosition }};
            const markerPosition = contentWidth * (todayPositionPercent / 100);
            const scrollPosition = Math.max(0, markerPosition - containerWidth / 2);

            timelineContainer.scrollLeft = scrollPosition;
            updateScrollIndicator();
        }, 100);
    }


    updateScrollIndicator();
});
    </script>
</x-layout>