# フォーム作成チュートリアル - 入力と保存

## 🎯 このチュートリアルで作るもの
簡単な「メモ投稿」機能を作ります。
- メモのタイトルと内容を入力できるフォーム
- 送信ボタンを押すとデータベースに保存
- 保存したメモの一覧表示

## 📋 事前準備

### 1. データベーステーブルを作る
```bash
php artisan make:migration create_memos_table
php artisan make:model Memo
```

### 2. テーブル設計
```php
// database/migrations/****_create_memos_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('title');         // メモのタイトル
            $table->text('content');         // メモの内容
            $table->timestamps();            // 作成日時・更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('memos');
    }
};
```

### 3. データベースに反映
```bash
php artisan migrate
```

### 4. モデル設定
```php
// app/Models/Memo.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    protected $fillable = ['title', 'content'];
}
```

## 🎮 コントローラー作成

### 1. コントローラーを作る
```bash
php artisan make:controller MemoController -r
```
**`-r`オプション:** CRUD操作メソッド（index, create, store, show, edit, update, destroy）を自動生成

### 2. 処理を書く
```php
// app/Http/Controllers/MemoController.php
<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    // フォーム表示
    public function create()
    {
        return view('memos.create');
    }
    
    // データ保存
    public function store(Request $request)
    {
        // データベースに保存
        Memo::create([
            'title' => $request->title,
            'content' => $request->content
        ]);
        
        // 一覧ページにリダイレクト
        return redirect()->route('memos.index')->with('success', 'メモを保存しました！');
    }
    
    // 一覧表示
    public function index()
    {
        $memos = Memo::orderBy('created_at', 'desc')->get();
        return view('memos.index', compact('memos'));
    }
}
```

## 🛣️ ルート設定
```php
// routes/web.php
use App\Http\Controllers\MemoController;

Route::get('/memos', [MemoController::class, 'index'])->name('memos.index');
Route::get('/memos/create', [MemoController::class, 'create'])->name('memos.create');
Route::post('/memos', [MemoController::class, 'store'])->name('memos.store');
```

## 📝 フォーム画面作成

### 1. 入力フォーム
```html
<!-- resources/views/memos/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>新しいメモ</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .form-group { margin: 15px 0; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>新しいメモを作成</h1>
    
    <form action="{{ route('memos.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title">タイトル:</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>
        
        <div class="form-group">
            <label for="content">内容:</label>
            <textarea name="content" id="content" rows="5" required>{{ old('content') }}</textarea>
        </div>
        
        <button type="submit">メモを保存</button>
        <a href="{{ route('memos.index') }}">一覧に戻る</a>
    </form>
</body>
</html>
```

### 2. 一覧画面
```html
<!-- resources/views/memos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>メモ一覧</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .memo { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .memo-title { font-size: 18px; font-weight: bold; color: #333; }
        .memo-content { margin: 10px 0; color: #666; }
        .memo-date { font-size: 12px; color: #999; }
        .btn { background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #218838; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>メモ一覧</h1>
    
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    
    <a href="{{ route('memos.create') }}" class="btn">新しいメモを作成</a>
    
    @if ($memos->count() > 0)
        @foreach ($memos as $memo)
            <div class="memo">
                <div class="memo-title">{{ $memo->title }}</div>
                <div class="memo-content">{{ $memo->content }}</div>
                <div class="memo-date">作成日: {{ $memo->created_at->format('Y年m月d日 H:i') }}</div>
            </div>
        @endforeach
    @else
        <p>まだメモがありません。最初のメモを作成してみましょう！</p>
    @endif
</body>
</html>
```

## 🔒 セキュリティ対策

### CSRFトークン
```html
<!-- フォームには必ず @csrf を入れる -->
<form action="{{ route('memos.store') }}" method="POST">
    @csrf
    <!-- フォーム内容 -->
</form>
```

### バリデーション（入力チェック）
```php
// app/Http/Controllers/MemoController.php の store メソッドを修正
public function store(Request $request)
{
    // 入力チェック
    $request->validate([
        'title' => 'required|max:100',     // 必須、100文字以内
        'content' => 'required|max:1000'   // 必須、1000文字以内
    ]);
    
    Memo::create([
        'title' => $request->title,
        'content' => $request->content
    ]);
    
    return redirect()->route('memos.index')->with('success', 'メモを保存しました！');
}
```

### エラー表示とold関数
```html
<!-- resources/views/memos/create.blade.php のフォーム上部に追加 -->
@if ($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- フォーム入力欄にold()関数を追加 -->
<div class="form-group">
    <label for="title">タイトル:</label>
    <input type="text" name="title" id="title" value="{{ old('title') }}" required>
</div>

<div class="form-group">
    <label for="content">内容:</label>
    <textarea name="content" id="content" rows="5" required>{{ old('content') }}</textarea>
</div>
```

**old()関数の効果:**
- バリデーションエラー時に入力した値を保持
- ユーザーが再入力する手間を省く
- フォームの使いやすさが大幅向上

## 🎯 動作確認手順

### 1. サーバー起動
```bash
php artisan serve
```

### 2. ブラウザでアクセス
- メモ一覧: `http://localhost:8000/memos`
- メモ作成: `http://localhost:8000/memos/create`

### 3. 動作テスト
1. 「新しいメモを作成」をクリック
2. タイトルと内容を入力
3. 「メモを保存」をクリック
4. 一覧ページに戻って保存されたメモを確認

## 💡 応用アイデア

### 1. メモ編集機能
```php
// routes/web.php に追加
Route::get('/memos/{memo}/edit', [MemoController::class, 'edit'])->name('memos.edit');
Route::put('/memos/{memo}', [MemoController::class, 'update'])->name('memos.update');
```

### 2. メモ削除機能
```php
Route::delete('/memos/{memo}', [MemoController::class, 'destroy'])->name('memos.destroy');
```

### 3. カテゴリ機能
- `category` カラムを追加
- カテゴリ別の表示機能

## 🆘 よくあるエラーと対処法

### エラー: 「Route [memos.index] not defined」
→ `routes/web.php` にルート定義があるか確認

### エラー: 「Call to undefined method」
→ モデルのuse文が正しいか確認

### データが保存されない
→ モデルの `$fillable` に保存したいカラム名があるか確認

### フォーム送信後に419エラー
→ フォームに `@csrf` があるか確認


## 🎉 完成！
これで入力フォームから データベースへの保存まで完了です。
ここから自分だけの機能を追加してみましょう！