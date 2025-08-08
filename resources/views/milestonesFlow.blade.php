@php
    use Carbon\Carbon;
    use Carbon\CarbonImmutable;
    
    $id = $goal['id'];
    
    $endDate = Carbon::parse($milestones->last()->endDate);
    
    $completedCount = $milestones->where('achieved', true)->count();
    $totalCount = count($milestones);
    $progressPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
    
    $today = Carbon::today();
@endphp

<x-layout :username=$username title="flow">
    <style>
        /* フロー接続線 */
        .flow-connector {
            width: 2px;
            height: 24px;
            background: linear-gradient(to bottom, #E5E7EB, #D1D5DB);
            margin: 0px auto;
        }

        .flow-connector.completed {
            background: linear-gradient(to bottom, #22C55E, #16A34A);
        }

        .flow-connector.in-progress {
            background: linear-gradient(to bottom, #22C55E, #3B82F6);
        }

        /* フローコンテナのスタイリング */
        .flow-container-wrapper {
            position: relative;
        }

        .flow-container-wrapper .task-card {
            position: relative;
            z-index: 2;
        }

        .flow-container-wrapper .flow-connector {
            position: relative;
            z-index: 1;
        }

        /* 目標達成後のスタイル */
        .goal-achieved {
            filter: grayscale(20%);
        }
        
        /* 詳細ボタンは目標達成後もクリック可能にする */
        .goal-achieved .detail-button {
            pointer-events: auto;
        }
        
        .goal-achieved input[type="checkbox"]:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* 達成済みバッジのアニメーション */
        .goal-achieved-badge {
            animation: fadeInDown 0.5s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        /* タスクカードのスタイル */
        .task-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .task-card.completed {
            background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
            border-color: #22C55E;
        }

        .task-card.in-progress {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border-color: #3B82F6;
        }

        .task-card.pending {
            background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
            border-color: #D1D5DB;
        }

        /* チェックボックスの改善 */
        input[type="checkbox"] {
            cursor: pointer;
            accent-color: #22C55E;
        }

        input[type="checkbox"]:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* 詳細ボタン */
        .detail-button {
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.2s ease;
        }

        .task-card:hover .detail-button {
            opacity: 1;
            transform: translateX(0);
        }

        /* 説明テキストの3行制限＆フェード */
        .task-desc {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            position: relative;
            max-height: 4.5em; /* 3行分 */
            line-height: 1.5em;
        }
        .task-desc.fade {
            /* 3行制限 */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            position: relative;
            max-height: 4.5em;
            line-height: 1.5em;
            /* テキストグラデーションマスク */
            -webkit-mask-image: linear-gradient(to bottom, #000 70%, transparent 100%);
            mask-image: linear-gradient(to bottom, #000 70%, transparent 100%);
        }
    </style>

    <!-- 目標タイトル -->
    <div class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $goal['name'] }}</h2>
        @if($endDate)
        <p class="text-sm text-gray-500">期限{{ $endDate->format('Y年m月d日') }}</p>
        @endif
    </div>

    <!-- 進捗サマリー -->
    <div class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100" id="progressSummary">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-gray-900">進捗状況</span>
            <span class="text-sm font-semibold text-primary-600" id="progressText">{{ $completedCount }}/{{ $totalCount }} 完了（{{ $progressPercentage }}%）</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-primary-500 h-2 rounded-full transition-all duration-500" id="progressBar" style="width: {{ $progressPercentage }}%"></div>
        </div>
    </div>
    <div class="flex justify-center my-4">
    <div class="inline-flex w-max bg-gray-100 rounded-lg p-1">
        <button class="px-3 py-1 text-xs font-semibold bg-white text-gray-900 rounded-md shadow-sm border border-gray-200">
            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
            フロー
        </button>
        <button class= "px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-800 rounded-md transition-colors" onclick="window.location.href='../milestones-wbs/{{ $id }}'">
            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            WBS
        </button>
    </div>
    </div>
    <!-- フロー表示 -->
    <div class="mb-6 flow-container-wrapper">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
            ステップフロー
        </h3>

        @if($milestones->count() > 0)
            @foreach($milestones as $index => $milestone)
            @php
                $isAchieved = $milestone['achieved'] ?? false;
                $start = CarbonImmutable::parse($milestone['startDate'])->startOfDay();
                $end = CarbonImmutable::parse($milestone['endDate'])->startOfDay();
                $isInProgress = !$isAchieved && $today->between($start, $end);
                $isFuture = $today->lt($start);
                
                $status = $isAchieved ? 'completed' : ($isInProgress ? 'in-progress' : 'pending');
                $statusText = $isAchieved ? '完了' : ($isInProgress ? '進行中' : '未着手');
                
                // チェックボックスが未チェック時の状態（これが元の状態として保存される）
                $uncheckedStatus = $today->between($start, $end) ? 'in-progress' : 'pending';
                
                $progressPercent = 0;
                if ($isAchieved) {
                    $progressPercent = 100;
                } elseif ($isInProgress) {
                    $totalDays = $start->diffInDays($end) + 1;
                    $elapsedDays = $start->diffInDays($today);
                    $progressPercent = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                }
                
                $iconClass = $isAchieved ? 'text-primary-500' : ($isInProgress ? 'text-secondary-500' : 'text-gray-400');
                $checkboxClass = $isAchieved ? 'text-primary-500' : ($isInProgress ? 'text-secondary-500' : 'text-gray-400');
                
                $period = $start->diffInDays($end) + 1;
                $onclick = "openTaskDetail(this," .
                    json_encode($milestone['name']) . "," .
                    json_encode($milestone['description'] ?? '説明なし') . "," .
                    json_encode($start->format('Y/m/d')) . "," .
                    json_encode($end->format('Y/m/d')) . "," .
                    json_encode($period) .
                ")";
            @endphp
            
            <!-- タスクカード -->
            <div class="task-card {{ $status }} bg-white rounded-xl p-4 border-2 shadow-sm" onclick="handleCardClick(event)" data-unchecked-status="{{ $uncheckedStatus }}">
                <div class="flex items-center">
                    <input type="checkbox" {{ $isAchieved ? 'checked' : '' }} 
                           class="w-5 h-5 rounded focus:ring-primary-100 mr-3 flex-shrink-0 {{ $isAchieved ? 'text-primary-500' : ($isInProgress ? 'text-secondary-500' : 'text-gray-400') }}" 
                           onclick="handleCheckboxClick(event)">
                    
                    <svg class="w-5 h-5 mr-3 {{ $iconClass }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $milestone['name'] }}</h4>
                        <p class="task-desc text-xs text-gray-600 mb-2 fade">{{ $milestone['description'] ?? '説明なし' }}</p>
                        <p class="text-xs text-gray-500">期間: {{ $start->diffInDays($end) + 1 }}日 | 
                            @if($isAchieved)
                                完了日: {{ $end->format('Y/m/d') }}
                            @elseif($isInProgress)
                                開始予定: {{ $start->format('Y/m/d') }}
                            @else
                                開始予定: {{ $start->format('Y/m/d') }}
                            @endif
                        </p>
                    </div>
                    
                    <button class="detail-button p-2 text-gray-400 hover:text-primary-500 hover:bg-primary-50 rounded-lg transition-colors flex-shrink-0 ml-2" 
                            onclick="event.stopPropagation(); {{ $onclick }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            @if($index < count($milestones) - 1)
            <div class="flow-connector {{ $status }}" data-connector-index="{{ $index }}"></div>
            @endif
            
            @endforeach
        @else
            <div class="flex items-center justify-center h-32">
                <div class="text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">マイルストーンがありません</p>
                </div>
            </div>
        @endif
    </div>
    <script>
        // イベントハンドラー
        function handleCardClick(event) {
            // 詳細ボタンやチェックボックスがクリックされた場合は何もしない
            if (event.target.closest('.detail-button') || event.target.closest('input[type="checkbox"]')) {
                return;
            }
            
            // カード内のチェックボックスを見つけてトグル
            const card = event.currentTarget;
            const checkbox = card.querySelector('input[type="checkbox"]');
            
            if (checkbox) {
                // チェックボックス状態を変更
                checkbox.checked = !checkbox.checked;
                
                // handleCheckboxClick関数を直接呼び出し（イベントオブジェクトを作成）
                const fakeEvent = {
                    target: checkbox,
                    stopPropagation: () => {}
                };
                handleCheckboxClick(fakeEvent);
            }
        }



        function handleCheckboxClick(event) {
            if (event.stopPropagation) {
                event.stopPropagation();
            }
            
            const checkbox = event.target;
            
            // チェックボックスとカードの存在確認
            if (!checkbox) {
                console.error('チェックボックスが見つかりません');
                return false;
            }
            
            const card = checkbox.closest('.task-card');
            if (!card) {
                console.error('タスクカードが見つかりません');
                return false;
            }
            
            const uncheckedStatus = card.getAttribute('data-unchecked-status');
            if (!uncheckedStatus) {
                console.error('未チェック状態の情報が見つかりません');
                return false;
            }

            // 全ての状態クラスを一度削除
            card.classList.remove('in-progress', 'pending', 'completed');

            // アイコンとチェックボックスの色も更新
            const icon = card.querySelector('svg');
            const checkboxElement = card.querySelector('input[type="checkbox"]');

            if (checkbox.checked) {
                // これが最後のマイルストーンかチェック
                if (isLastMilestone(checkbox)) {
                    // 最後のマイルストーンの場合は確認ダイアログを表示
                    showFinalMilestoneConfirmation(checkbox, card, icon, checkboxElement);
                } else {
                    // 通常のマイルストーン完了処理
                    completeMilestone(card, icon, checkboxElement);
                }
            } else {
                // チェックを外した時の処理 - 未チェック時の適切な状態に戻す
                card.classList.add(uncheckedStatus);
                
                // アイコンとチェックボックスの色を状態に応じて更新
                const colorClass = uncheckedStatus === 'in-progress' ? 'text-secondary-500' : 'text-gray-400';
                updateElementColor(icon, colorClass);
                updateElementColor(checkboxElement, colorClass);
                
                // 接続線の色も更新
                updateConnectorColors();
                
                updateProgressSummary();
                refreshOpenDetailSheetIfAny();
            }
            
            return true;
        }

        // 最後のマイルストーンかどうかをチェックする関数
        function isLastMilestone(checkbox) {
            const allCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]');
            const checkedCount = document.querySelectorAll('.task-card input[type="checkbox"]:checked').length;
            
            // 現在のチェックボックスを除いて、他が全て完了しているかチェック
            let otherCompletedCount = 0;
            allCheckboxes.forEach(cb => {
                if (cb !== checkbox && cb.checked) {
                    otherCompletedCount++;
                }
            });
            
            return otherCompletedCount === allCheckboxes.length - 1;
        }

        // 通常のマイルストーン完了処理
        function completeMilestone(card, icon, checkboxElement) {
            showNotification('ステップ完了！お疲れさまでした', 'success');
            card.classList.add('completed');
            
            // アイコンとチェックボックスを緑色に
            updateElementColor(icon, 'text-primary-500');
            updateElementColor(checkboxElement, 'text-primary-500');
            
            // 接続線の色も更新
            updateConnectorColors();
            
            updateProgressSummary();
            refreshOpenDetailSheetIfAny();
        }

        // 最後のマイルストーン確認ダイアログを表示する関数
        function showFinalMilestoneConfirmation(checkbox, card, icon, checkboxElement) {
            // チェックボックスを一旦戻す
            checkbox.checked = false;
            
            const content = `
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🎉 おめでとうございます！</h3>
                    <p class="text-gray-600 mb-6">これが最後のステップです！<br>完了すると目標達成となります。<br>本当に完了しますか？</p>
                    
                    <div class="flex space-x-3">
                        <button onclick="confirmFinalMilestone('${card.className}', '${icon.className}', '${checkboxElement.className}')" 
                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200">
                            完了して目標達成
                        </button>
                        <button onclick="hideTaskDetail()" 
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors duration-200">
                            キャンセル
                        </button>
                    </div>
                </div>
            `;

            // 一時的にチェックボックスとカード情報を保存
            window.pendingFinalMilestone = {
                checkbox: checkbox,
                card: card,
                icon: icon,
                checkboxElement: checkboxElement
            };

            document.getElementById('taskDetailContent').innerHTML = content;
            document.querySelector('.bottom-sheet-overlay').classList.add('show');
            document.getElementById('taskDetailSheet').classList.add('show');

            // 特別なバイブレーション
            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }
        }

        // 最後のマイルストーン完了を確定する関数
        function confirmFinalMilestone() {
            const pending = window.pendingFinalMilestone;
            if (!pending) return;
            
            // チェックボックスを完了状態に
            pending.checkbox.checked = true;
            
            // 完了処理を実行
            completeMilestone(pending.card, pending.icon, pending.checkboxElement);
            
            // ダイアログを閉じる
            hideTaskDetail();
            
            // 目標達成の特別な通知とconfettiエフェクト
            setTimeout(() => {
                showNotification('🎊 目標達成！素晴らしいです！', 'success');
                triggerGoalAchievementCelebration();
            }, 200);
            
            // 一時データをクリア
            window.pendingFinalMilestone = null;
            
            // 目標達成後、全てのマイルストーンを編集不可にする
            disableAllMilestones();
            
            // お祝いのバイブレーション
            if (navigator.vibrate) {
                navigator.vibrate([200, 100, 200, 100, 200]);
            }
        }

        // 目標達成後、全てのマイルストーンを編集不可にする関数
        function disableAllMilestones() {
            const allCards = document.querySelectorAll('.task-card');
            const allCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]');
            const allDetailButtons = document.querySelectorAll('.detail-button');
            
            // 全てのチェックボックスを無効化
            allCheckboxes.forEach(checkbox => {
                checkbox.disabled = true;
                checkbox.style.cursor = 'not-allowed';
            });
            
            // 全てのカードを編集不可スタイルに変更
            allCards.forEach(card => {
                card.style.cursor = 'default';
                card.style.opacity = '0.8';
                card.classList.add('goal-achieved');
                
                // カードクリックイベントを無効化
                card.onclick = null;
                
                // ホバー効果を無効化
                card.style.transition = 'none';
                
                // 詳細ボタンだけは有効のまま保持
                const detailButton = card.querySelector('.detail-button');
                if (detailButton) {
                    detailButton.style.pointerEvents = 'auto';
                    detailButton.style.opacity = '1';
                    detailButton.style.cursor = 'pointer';
                }
            });
            
            // 詳細ボタンは有効のまま（詳細は見られるが編集はできない）
            // 詳細ボタンはそのまま動作するようにする
            
            // 達成済みバッジを追加
            addGoalAchievedBadge();
        }

        // 目標達成バッジを追加する関数
        function addGoalAchievedBadge() {
            const flowContainer = document.querySelector('.flow-container') || document.querySelector('.p-4');
            
            if (flowContainer && !document.querySelector('.goal-achieved-badge')) {
                const badge = document.createElement('div');
                badge.className = 'goal-achieved-badge fixed top-20 left-1/2 transform -translate-x-1/2 bg-primary-500 text-white px-4 py-2 rounded-full shadow-lg z-50 animate-pulse';
                badge.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">🏆 目標達成済み</span>
                    </div>
                `;
                
                document.body.appendChild(badge);
                
                // 3秒後にバッジを非表示にする
                setTimeout(() => {
                    badge.style.opacity = '0';
                    badge.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        if (badge.parentNode) {
                            badge.parentNode.removeChild(badge);
                        }
                    }, 500);
                }, 3000);
            }
        }

        // 進捗サマリーを動的に更新する関数
        function updateProgressSummary() {
            const allCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]');
            const checkedCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]:checked');
            
            const totalCount = allCheckboxes.length;
            const completedCount = checkedCheckboxes.length;
            const progressPercentage = totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : 0;
            
            // テキストを更新
            const progressText = document.getElementById('progressText');
            if (progressText) {
                progressText.textContent = `${completedCount}/${totalCount} 完了（${progressPercentage}%）`;
            }
            
            // プログレスバーを更新
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                progressBar.style.width = `${progressPercentage}%`;
            }
            
            // 目標達成時の特別な表示
            if (progressPercentage === 100) {
                const progressSummary = document.getElementById('progressSummary');
                if (progressSummary && !progressSummary.classList.contains('goal-completed')) {
                    progressSummary.classList.add('goal-completed');
                    
                    // 達成時の特別なスタイルを追加
                    setTimeout(() => {
                        progressSummary.style.background = 'linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%)';
                        progressSummary.style.borderColor = '#22C55E';
                        progressText.innerHTML = `🎉 ${completedCount}/${totalCount} 完了（${progressPercentage}%）目標達成！`;
                    }, 500);
                }
            } else {
                // 未達成の場合は通常の表示に戻す
                const progressSummary = document.getElementById('progressSummary');
                if (progressSummary && progressSummary.classList.contains('goal-completed')) {
                    progressSummary.classList.remove('goal-completed');
                    progressSummary.style.background = '';
                    progressSummary.style.borderColor = '';
                }
            }
        }

        // 目標達成お祝いエフェクトを発生させる関数
        function triggerGoalAchievementCelebration() {
            // 画面下部から上に向かって豪華なconfettiを発射
            const duration = 3000; // 3秒間
            const animationEnd = Date.now() + duration;
            const defaults = { 
                startVelocity: 30, 
                spread: 360, 
                ticks: 60, 
                zIndex: 9999 
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            // 連続してconfettiを発射する関数
            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);

                // 左下から
                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                }));

                // 右下から
                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                }));
            }, 250);

            // 最初に大きな爆発を中央下から
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.9 },
                colors: ['#22C55E', '#3B82F6', '#EC4899', '#F59E0B'],
                zIndex: 9999
            });

            // 1秒後に追加の爆発
            setTimeout(() => {
                confetti({
                    particleCount: 80,
                    spread: 100,
                    origin: { x: 0.2, y: 0.8 },
                    colors: ['#22C55E', '#3B82F6', '#EC4899', '#F59E0B'],
                    zIndex: 9999
                });
                
                confetti({
                    particleCount: 80,
                    spread: 100,
                    origin: { x: 0.8, y: 0.8 },
                    colors: ['#22C55E', '#3B82F6', '#EC4899', '#F59E0B'],
                    zIndex: 9999
                });
            }, 1000);

            // 2秒後に最後の華やかな爆発
            setTimeout(() => {
                confetti({
                    particleCount: 120,
                    spread: 160,
                    origin: { y: 0.7 },
                    colors: ['#22C55E', '#3B82F6', '#EC4899', '#F59E0B', '#FBBF24'],
                    zIndex: 9999
                });
            }, 2000);
        }

        // 接続線の色を更新する関数
        function updateConnectorColors() {
            const allCards = document.querySelectorAll('.task-card');
            const connectors = document.querySelectorAll('.flow-connector');
            
            connectors.forEach((connector, index) => {
                if (index >= allCards.length - 1) return;
                
                // 上のカード（現在のインデックス）と下のカード（次のインデックス）
                const currentCard = allCards[index];
                const nextCard = allCards[index + 1];
                
                if (!currentCard || !nextCard) return;
                
                // 両方のカードの状態を確認
                const currentStatus = getCardStatus(currentCard);
                const nextStatus = getCardStatus(nextCard);
                
                // 接続線の色を決定
                let connectorClass = '';
                if (currentStatus === 'completed' && nextStatus === 'completed') {
                    // 両方完了：緑
                    connectorClass = 'completed';
                } else if (currentStatus === 'completed' || nextStatus === 'completed') {
                    // どちらか完了：緑
                    connectorClass = 'completed';
                } else if (currentStatus === 'in-progress' || nextStatus === 'in-progress') {
                    // どちらか進行中：青
                    connectorClass = 'in-progress';
                } else {
                    // 両方未着手：グレー
                    connectorClass = 'pending';
                }
                
                // 既存のステータスクラスを削除して新しいクラスを追加
                connector.classList.remove('completed', 'in-progress', 'pending');
                connector.classList.add(connectorClass);
            });
        }

        // カードの現在の状態を取得する関数
        function getCardStatus(card) {
            if (card.classList.contains('completed')) return 'completed';
            if (card.classList.contains('in-progress')) return 'in-progress';
            if (card.classList.contains('pending')) return 'pending';
            return 'pending'; // デフォルト
        }

        // 要素の色クラスを更新する関数
        function updateElementColor(element, newColorClass) {
            if (!element) return;
            
            // 既存の色クラスを削除
            const colorClasses = [
                'text-primary-500', 'text-primary-600', 'text-primary-700', 'text-primary-800',
                'text-secondary-500', 'text-secondary-600', 'text-secondary-700', 'text-secondary-800',
                'text-gray-400', 'text-gray-500', 'text-gray-600', 'text-gray-700'
            ];
            
            colorClasses.forEach(colorClass => {
                element.classList.remove(colorClass);
            });
            
            // 新しい色クラスを追加
            element.classList.add(newColorClass);
        }



        // 初期化
        document.addEventListener('DOMContentLoaded', function() {
            // 接続線の色を初期化
            updateConnectorColors();
            // 進捗サマリーを初期化
            updateProgressSummary();
            // 初期状態で目標が達成済みかチェック
            checkInitialGoalStatus();
        });

        // 初期状態で目標が達成済みかチェックする関数
        function checkInitialGoalStatus() {
            const allCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]');
            const checkedCount = document.querySelectorAll('.task-card input[type="checkbox"]:checked').length;
            
            // 全てのマイルストーンが既に完了している場合
            if (allCheckboxes.length > 0 && checkedCount === allCheckboxes.length) {
                // 編集不可にする
                disableAllMilestones();
                
                // 達成済みバッジを表示（少し短めに）
                setTimeout(() => {
                    addGoalAchievedBadge();
                }, 500);
            }
        }

        // 目標が達成済みかチェックする関数
        function checkIfGoalAchieved() {
            const allCheckboxes = document.querySelectorAll('.task-card input[type="checkbox"]');
            const checkedCount = document.querySelectorAll('.task-card input[type="checkbox"]:checked').length;
            
            return allCheckboxes.length > 0 && checkedCount === allCheckboxes.length;
        }

        // 詳細ボタンから最新状態でシートを開く
        function openTaskDetail(button, name, description, start, end, period) {
            const card = button.closest('.task-card');
            if (!card) return;
            const status = getCardStatus(card);
            showTaskDetail(name, description, start, end, status, period);
        }

        // 開いている詳細シートがあれば、対象カードの最新状態で再描画
        function refreshOpenDetailSheetIfAny() {
            const sheet = document.getElementById('taskDetailSheet');
            const overlay = document.querySelector('.bottom-sheet-overlay');
            if (!sheet || !overlay) return;
            const isOpen = sheet.classList.contains('show') && overlay.classList.contains('show');
            if (!isOpen) return;
            const content = document.getElementById('taskDetailContent');
            if (!content) return;
            // 直近に開いたカード要素を特定できない設計のため、何もしない（open時に最新を渡しているため表示は最新）
        }
    </script>
</x-layout>
