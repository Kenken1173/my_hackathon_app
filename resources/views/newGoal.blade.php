<x-layout :username=$username title="test" :footerFlag="false">

<style>
.input-focus:focus {
    border-color: #22C55E;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}
.step-indicator {
    transition: all 0.3s ease;
}
.step-indicator.active {
    background: #22C55E;
    color: white;
}
.step-indicator.completed {
    background: #22C55E;
    color: white;
}
.progress-bar {
    transition: width 0.5s ease;
}
</style>

<div class="p-4 max-w-md mx-auto">
    <!-- プログレス表示 -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600">ステップ <span id="currentStep">1</span> / 3</span>
            <span class="text-sm text-primary-600 font-medium" id="stepTitle">基本情報</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="progress-bar bg-primary-500 h-2 rounded-full" style="width: 33%"></div>
        </div>
    </div>

    <!-- ステップインジケーター -->
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center space-x-2">
            <div class="step-indicator active w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold bg-primary-500 text-white">1</div>
            <div class="w-8 h-0.5 bg-gray-200"></div>
            <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-500">2</div>
            <div class="w-8 h-0.5 bg-gray-200"></div>
            <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-500">3</div>
        </div>
    </div>

    <!-- フォーム -->
    <form id="goalForm" action="{{ route('goal.new') }}" method="POST" class="space-y-6">
        @csrf
        <!-- ステップ1: 基本情報 -->
        <div id="step1" class="step-content">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">目標を設定しましょう</h2>
                    <p class="text-sm text-gray-600">まずは目標の基本情報を入力してください</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="goalTitle" class="block text-sm font-medium text-gray-700 mb-2">
                            目標タイトル <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="goalTitle" 
                            name="goalTitle" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none input-focus transition-all duration-200"
                            placeholder="例：TOEICで800点を取る"
                            required
                        >
                    </div>

                    <div>
                        <label for="goalDescription" class="block text-sm font-medium text-gray-700 mb-2">
                            詳細説明
                        </label>
                        <textarea 
                            id="goalDescription" 
                            name="goalDescription"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none input-focus transition-all duration-200 resize-none"
                            placeholder="目標の詳細や理由を書いてください（任意）"
                        ></textarea>
                    </div>

                    <div>
                        <label for="goalDeadline" class="block text-sm font-medium text-gray-700 mb-2">
                            達成期限 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="goalDeadline" 
                            name="goalDeadline"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none input-focus transition-all duration-200"
                            required
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- ステップ2: カテゴリー選択 -->
        <div id="step2" class="step-content hidden">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">カテゴリーを選択</h2>
                    <p class="text-sm text-gray-600">目標に最適なカテゴリーを選んでください</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="category-option p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50" data-category="study">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">学習・資格</span>
                        </div>
                    </div>

                    <div class="category-option p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50" data-category="health">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">健康・運動</span>
                        </div>
                    </div>

                    <div class="category-option p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50" data-category="work">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 002 2H6a2 2 0 002-2V6"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">仕事・キャリア</span>
                        </div>
                    </div>

                    <div class="category-option p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50" data-category="hobby">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1a3 3 0 015.656 0H16m-7 4h.01M17 8v8a2 2 0 01-2 2H9a2 2 0 01-2-2V8c0-.553.448-1 1-1h8c.552 0 1 .447 1 1z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">趣味・娯楽</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="selectedCategory" name="category">
            </div>
        </div>

        <!-- ステップ3: 確認・設定 -->
        <div id="step3" class="step-content hidden">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">設定を確認</h2>
                    <p class="text-sm text-gray-600">入力内容を確認して目標を作成してください</p>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-700 mb-1">目標タイトル</div>
                        <div id="confirmTitle" class="text-gray-900"></div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-700 mb-1">詳細説明</div>
                        <div id="confirmDescription" class="text-gray-900"></div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-700 mb-1">カテゴリー</div>
                        <div id="confirmCategory" class="text-gray-900"></div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-700 mb-1">達成期限</div>
                        <div id="confirmDeadline" class="text-gray-900"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ナビゲーションボタン -->
        <div class="flex space-x-3 pt-4">
            <button type="button" id="prevBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition-colors duration-200 hidden">
                戻る
            </button>
            <button type="button" id="nextBtn" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200">
                次へ
            </button>
            <button type="submit" id="submitBtn" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 hidden">
                目標を作成
            </button>
        </div>
    </form>

    <div class="mt-8 text-center">
        <p class="text-xs text-gray-500">
            作成した目標は後から編集することができます
        </p>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

// ステップ管理
function showStep(step) {
    // すべてのステップを非表示
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // 指定のステップを表示
    document.getElementById(`step${step}`).classList.remove('hidden');
    
    // ステップインジケーター更新
    updateStepIndicators(step);
    
    // プログレスバー更新
    updateProgressBar(step);
    
    // ボタン表示制御
    updateButtons(step);
    
    // ステップタイトル更新
    updateStepTitle(step);
    
    currentStep = step;
}

function updateStepIndicators(step) {
    const indicators = document.querySelectorAll('.step-indicator');
    indicators.forEach((indicator, index) => {
        const stepNumber = index + 1;
        if (stepNumber < step) {
            indicator.classList.add('completed');
            indicator.classList.remove('active');
        } else if (stepNumber === step) {
            indicator.classList.add('active');
            indicator.classList.remove('completed');
        } else {
            indicator.classList.remove('active', 'completed');
        }
    });
}

function updateProgressBar(step) {
    const progress = (step / totalSteps) * 100;
    document.querySelector('.progress-bar').style.width = `${progress}%`;
    document.getElementById('currentStep').textContent = step;
}

function updateButtons(step) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (step === 1) {
        prevBtn.classList.add('hidden');
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    } else if (step === totalSteps) {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

function updateStepTitle(step) {
    const titles = ['基本情報', 'カテゴリー', '確認・設定'];
    document.getElementById('stepTitle').textContent = titles[step - 1];
}

// カテゴリー選択
document.addEventListener('DOMContentLoaded', function() {
    const categoryOptions = document.querySelectorAll('.category-option');
    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {
            // すべての選択を解除
            categoryOptions.forEach(opt => {
                opt.classList.remove('border-primary-500', 'bg-primary-100');
                opt.classList.add('border-gray-200');
            });
            
            // 選択されたオプションをハイライト
            this.classList.remove('border-gray-200');
            this.classList.add('border-primary-500', 'bg-primary-100');
            
            // 隠しフィールドに値を設定
            const category = this.dataset.category;
            document.getElementById('selectedCategory').value = category;
        });
    });
    
    // 次へボタン
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validateCurrentStep()) {
            if (currentStep === 2) {
                updateConfirmation();
            }
            showStep(currentStep + 1);
        }
    });
    
    // 戻るボタン
    document.getElementById('prevBtn').addEventListener('click', function() {
        showStep(currentStep - 1);
    });
    
    // フォーム送信
    // document.getElementById('goalForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     document.getElementById('goalForm').submit();

    //     // 成功メッセージ表示
    //     showSuccessMessage();
    // });
});

function validateCurrentStep() {
    if (currentStep === 1) {
        const title = document.getElementById('goalTitle').value.trim();
        const deadline = document.getElementById('goalDeadline').value;
        
        if (!title) {
            showError('目標タイトルを入力してください');
            return false;
        }
        if (!deadline) {
            showError('達成期限を選択してください');
            return false;
        }
    } else if (currentStep === 2) {
        const category = document.getElementById('selectedCategory').value;
        if (!category) {
            showError('カテゴリーを選択してください');
            return false;
        }
    }
    return true;
}

function updateConfirmation() {
    const title = document.getElementById('goalTitle').value;
    const description = document.getElementById('goalDescription').value;
    const category = document.getElementById('selectedCategory').value;
    const deadline = document.getElementById('goalDeadline').value;
    
    const categoryNames = {
        study: '学習・資格',
        health: '健康・運動',
        work: '仕事・キャリア',
        hobby: '趣味・娯楽'
    };
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmDescription').textContent = description || 'なし';
    document.getElementById('confirmCategory').textContent = categoryNames[category];
    document.getElementById('confirmDeadline').textContent = new Date(deadline).toLocaleDateString('ja-JP');
}

function showError(message) {
    // 簡単なエラー表示
    alert(message);
}

function showSuccessMessage() {
    // 成功メッセージ表示（実際の実装では適切なUIで表示）
    alert('目標が作成されました！マイルストーン一覧に戻ります。');
    // 次のページにリダイレクト
    window.location.href = "/"
}
</script>
</body>
</x-layout>