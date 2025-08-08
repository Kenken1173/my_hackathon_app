<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
                <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>{{ $title ?? 'GoalMapper' }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
        tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': {
                        50: '#F0FDF4', 100: '#DCFCE7', 200: '#BBF7D0', 300: '#86EFAC', 400: '#4ADE80',
                        500: '#22C55E', 600: '#16A34A', 700: '#15803D', 800: '#166534', 900: '#14532D'
                    },
                    'secondary': {
                        50: '#EFF6FF', 100: '#DBEAFE', 200: '#BFDBFE', 300: '#93C5FD', 400: '#60A5FA',
                        500: '#3B82F6', 600: '#2563EB', 700: '#1D4ED8', 800: '#1E40AF', 900: '#1E3A8A'
                    },
                    'accent': {
                        50: '#FDF2F8', 100: '#FCE7F3', 200: '#FBCFE8', 300: '#F9A8D4', 400: '#F472B6',
                        500: '#EC4899', 600: '#DB2777', 700: '#BE185D', 800: '#9D174D', 900: '#831843'
                    },
                    'warning': {
                        50: '#FFFBEB', 100: '#FEF3C7', 200: '#FDE68A', 300: '#FCD34D', 400: '#FBBF24',
                        500: '#F59E0B', 600: '#D97706', 700: '#B45309', 800: '#92400E', 900: '#78350F'
                    }
                }
            }
        }
        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

        <style>
            body { font-family: 'Noto Sans JP', sans-serif; }
            
            /* ボトムシート共通スタイル */
            .bottom-sheet {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-radius: 20px 20px 0 0;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
                transform: translateY(100%);
                transition: transform 0.3s ease;
                z-index: 1000;
                max-height: 70vh;
                overflow-y: auto;
            }

            .bottom-sheet.show {
                transform: translateY(0);
            }

            .bottom-sheet-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                z-index: 999;
            }

            .bottom-sheet-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            /* 通知トーストのスタイル */
            .notification-toast {
                animation: slideInDown 0.3s ease-out;
            }

            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translate(-50%, -20px);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, 0);
                }
            }
        </style>
    </head>
    <body>
        <!-- 共通ヘッダー -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if(request()->path() !== '/')
                            <button class="mr-3 p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="history.back()">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            </button>  
                        @endif
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold text-gray-900">GoalMapper</h1>
                    </div>
                    <div class="flex justify-between items-center space-x-4">
                        <p>{{ $username ?? 'unknown' }}</p>
                        <div class="relative" id="userMenu">
                            <button id="userMenuButton" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors focus:outline-none">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </button>
                            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">ログアウト</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-4 max-w-md mx-auto pb-24">
            {{ $slot }}
        </div>

        <!-- 共通ナビゲーションバー -->
        @if ($footerFlag ?? true)
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 shadow-sm z-50">
            <div class="flex justify-around max-w-md mx-auto">
                <button class="flex flex-col items-center py-2 px-3 {{ ($nav ?? 'home') == 'home' ? 'text-primary-500' : 'text-gray-400 hover:text-gray-600 transition-colors' }}">
                    <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span class="text-xs font-semibold">マイ目標</span>
                </button>
                <button class="flex flex-col items-center py-2 px-3 {{ ($nav ?? '') == 'new' ? 'text-primary-500' : 'text-gray-400 hover:text-gray-600 transition-colors' }}" onclick="window.location.href='goal-input.html'">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-xs">新しい目標</span>
                </button>
            </div>
        </nav>
        @endif

        <!-- 共通ボトムシートオーバーレイ -->
        <div class="bottom-sheet-overlay" onclick="hideTaskDetail()"></div>

        <!-- 共通ボトムシート -->
        <div class="bottom-sheet" id="taskDetailSheet">
            <div class="p-4">
                <!-- ハンドル -->
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
                
                <!-- タスク詳細コンテンツ -->
                <div id="taskDetailContent">
                    <!-- JavaScriptで動的に生成 -->
                </div>
            </div>
        </div>

        <script>
            // 共通タスク詳細表示関数
            function showTaskDetail(name, description, startDate, endDate, status, period, options = {}) {
                const statusConfig = {
                    completed: { color: 'primary', text: '完了', bgColor: 'bg-primary-100', textColor: 'text-primary-800' },
                    'in-progress': { color: 'secondary', text: '進行中', bgColor: 'bg-secondary-100', textColor: 'text-secondary-800' },
                    pending: { color: 'gray', text: '未着手', bgColor: 'bg-gray-100', textColor: 'text-gray-600' }
                };

                const statusInfo = statusConfig[status] || statusConfig.pending;

                // 基本的なコンテンツ
                let content = `
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900">${name}</h3>
                            <span class="text-xs ${statusInfo.bgColor} ${statusInfo.textColor} px-3 py-1 rounded-full font-medium">
                                ${statusInfo.text}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">${description}</p>
                    </div>
                `;

                // 進捗情報があれば表示
                if (options.progress !== undefined) {
                    content += `
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">進捗</span>
                                <span class="text-sm font-semibold text-${statusInfo.color}-600">${options.progress}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-${statusInfo.color}-500 h-2 rounded-full transition-all duration-300" style="width: ${options.progress}%"></div>
                            </div>
                        </div>
                    `;
                }

                // 期間情報
                content += `
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">期間</span>
                            <span class="font-medium text-gray-700">${period}日間</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">${startDate}〜${endDate}</div>
                    </div>
                `;

                // カスタムアクションボタンがあれば追加
                if (options.actionButton) {
                    content += options.actionButton;
                }

                // 閉じるボタン
                content += `
                    <button onclick="hideTaskDetail()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors duration-200">
                        閉じる
                    </button>
                `;

                document.getElementById('taskDetailContent').innerHTML = content;

                document.querySelector('.bottom-sheet-overlay').classList.add('show');
                document.getElementById('taskDetailSheet').classList.add('show');

                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }

            // 共通ボトムシート非表示関数
            function hideTaskDetail() {
                document.querySelector('.bottom-sheet-overlay').classList.remove('show');
                document.getElementById('taskDetailSheet').classList.remove('show');
            }

            // ESCキーでボトムシートを閉じる
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideTaskDetail();
                }
            });

            // 共通通知表示関数
            function showNotification(message, type = 'info') {
                // 既存の通知を削除（重複防止）
                const existingNotifications = document.querySelectorAll('.notification-toast');
                existingNotifications.forEach(notification => notification.remove());

                const colors = {
                    success: 'bg-primary-500 text-white',
                    warning: 'bg-warning-500 text-white',
                    info: 'bg-secondary-500 text-white',
                    error: 'bg-red-500 text-white'
                };

                const notification = document.createElement('div');
                notification.className = `notification-toast fixed top-20 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-xl text-sm font-semibold z-50 transition-all duration-300 shadow-lg ${colors[type] || colors.info}`;
                notification.textContent = message;
                notification.style.opacity = '0';
                document.body.appendChild(notification);

                // アニメーション開始
                setTimeout(() => {
                    notification.style.opacity = '1';
                }, 10);

                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }, 2500);
            }

            (function() {
                var toggleButton = document.getElementById('userMenuButton');
                var dropdown = document.getElementById('userMenuDropdown');
                var container = document.getElementById('userMenu');
                if (!container || !toggleButton || !dropdown) return;
                document.addEventListener('click', function(e) {
                    if (container.contains(e.target)) {
                        if (e.target.closest('#userMenuButton')) {
                            dropdown.classList.toggle('hidden');
                        }
                    } else {
                        dropdown.classList.add('hidden');
                    }
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdown.classList.add('hidden');
                    }
                });
            })();
        </script>
    </body>
</html>
