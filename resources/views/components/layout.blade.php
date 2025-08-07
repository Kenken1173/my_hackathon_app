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

        <style>
            body { font-family: 'Noto Sans JP', sans-serif; }
        </style>
    </head>
    <body>
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
                    
                    <!-- プロフィールボタン -->
                    <div class="flex justify-between items-center space-x-4">
                        <p>{{ $name ?? 'unknown' }}</p>
                        <button class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-4 max-w-md mx-auto pb-24">
        {{ $slot }}
        </div>
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 shadow-sm">
            <div class="flex justify-around max-w-md mx-auto">
                <button class="flex flex-col items-center py-2 px-3 text-primary-500">
                    <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span class="text-xs font-semibold">マイ目標</span>
                </button>
                <button class="flex flex-col items-center py-2 px-3 text-gray-400 hover:text-gray-600 transition-colors" onclick="window.location.href='goal-input.html'">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-xs">新しい目標</span>
                </button>
                <button class="flex flex-col items-center py-2 px-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-xs">進捗</span>
                </button>
            </div>
        </nav>
    </body>
</html>
