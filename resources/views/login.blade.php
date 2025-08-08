<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FutureMirror - ログイン</title>
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
                }
            }
        }
    }
}
</script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Noto Sans JP', sans-serif; }
.gradient-bg {
    background: linear-gradient(135deg, #F0FDF4 0%, #EFF6FF 50%, #FDF2F8 100%);
    background-size: 200% 200%;
    animation: gradientMove 8s ease-in-out infinite;
}
@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.glass-effect {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.input-focus:focus {
    border-color: #22C55E;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}
</style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
    <!-- ロゴとタイトル -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-primary-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">FutureMirror</h1>
        <p class="text-gray-600">目標達成への道筋を描く</p>
    </div>

    <!-- ログインフォーム -->
    <div class="glass-effect rounded-2xl shadow-xl p-8">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">ログイン</h2>
            <p class="text-sm text-gray-600">アカウントにサインインしてください</p>
        </div>

        <form class="space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス</label>
                <input 
                    type="email"
                    name="email"
                    id="email" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none input-focus transition-all duration-200"
                    placeholder="example@email.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">パスワード</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password"
                        id="password" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none input-focus transition-all duration-200 pr-12"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input name="remember" type="checkbox" class="w-4 h-4 text-primary-500 rounded border-gray-300 focus:ring-primary-100">
                    <span class="ml-2 text-sm text-gray-600">ログイン状態を保持</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 transition-colors">パスワードを忘れた？</a>
                @endif
            </div>

            <button 
                type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg"
            >
                ログイン
            </button>
        </form>
</div>

<script>
// パスワード表示切り替え
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const toggleButton = passwordInput.nextElementSibling;
    
    toggleButton.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        
        // アイコンの切り替え
        const icon = this.querySelector('svg');
        if (type === 'text') {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            `;
        } else {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    });
});
</script>
</body>
</html>