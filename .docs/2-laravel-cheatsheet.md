# Laravel学生ハッカソン チートシート

## 🌟 Laravelって何？
Webサイトを作るためのツール（フレームワーク）です。HTMLやCSSだけでは作れない動的なWebサイトが簡単に作れます。

## 🚀 開発を始める
```bash
php artisan serve
```
このコマンドで開発サーバーが起動します。ブラウザで `http://localhost:8000` にアクセス！

## 📂 重要なフォルダ（最初はこれだけ覚えて）
- `routes/web.php` - どのURLで何を表示するか決める場所
- `resources/views/` - ユーザーが見る画面（HTML）を置く場所
- `app/Http/Controllers/` - 処理をまとめて書く場所（後で説明）

## 🛣️ ページを作る手順

### 1. URLを決める
```php
// routes/web.php に追加
Route::get('/about', function () {
    return view('about');
});
```
→ `/about` というURLでページが見れるようになる

### 2. 画面を作る
```html
<!-- resources/views/about.blade.php を新規作成 -->
<!DOCTYPE html>
<html>
<head>
    <title>私について</title>
</head>
<body>
    <h1>自己紹介ページ</h1>
    <p>こんにちは！私の名前は{{ $name ?? '名無し' }}です。</p>
</body>
</html>
```

### 3. データを渡す
```php
// routes/web.php で変更
Route::get('/about', function () {
    return view('about', ['name' => '田中太郎']);
});
```

## 🎮 処理をまとめる（Controller）
複雑な処理は別ファイルにまとめると管理しやすくなります。

### 1. Controllerを作る
```bash
php artisan make:controller AboutController
```

**CRUD操作が必要な場合は`-r`オプション推奨:**
```bash
php artisan make:controller MemoController -r
```
→ index, create, store, show, edit, update, destroyメソッドが自動生成

### 2. 処理を書く
```php
// app/Http/Controllers/AboutController.php に追加
<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function show()
    {
        $name = '田中太郎';
        $age = 20;
        
        return view('about', [
            'name' => $name,
            'age' => $age
        ]);
    }
}
```

### 3. ルートで使う
```php
// routes/web.php
use App\Http\Controllers\AboutController;

Route::get('/about', [AboutController::class, 'show']);
```

## 💡 よく使う書き方

### 変数を表示
```html
<p>こんにちは、{{ $name }}さん！</p>
```

### if文（条件分岐）
```html
@if($age >= 20)
    <p>大人です</p>
@else
    <p>未成年です</p>
@endif
```

### 繰り返し
```html
@foreach($items as $item)
    <li>{{ $item }}</li>
@endforeach
```

## 🎯 最初に作ってみよう
1. 自己紹介ページ（`/profile`）
2. 好きなもの一覧ページ（`/favorites`）
3. お問い合わせページ（`/contact`）

## 🆘 困ったとき
- エラーが出たら画面をよく読む（英語でもなんとなく分かる）
- `php artisan serve` をもう一度実行
- ブラウザを更新（F5キー）
