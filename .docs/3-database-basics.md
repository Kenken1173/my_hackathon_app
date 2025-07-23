# データベース超入門 - SQLite編

## 🗃️ データベースって何？
データを整理して保存しておく「デジタルの引き出し」のようなものです。
Excelの表のようにデータを行と列で管理します。

## 📊 RDB（リレーショナルデータベース）とは？
複数の表（テーブル）を組み合わせてデータを管理する仕組みです。

### 例：ブログサイトの場合
- **usersテーブル** → ユーザー情報を保存
- **postsテーブル** → 投稿内容を保存
- この2つのテーブルを関連付けて「誰がどの投稿をしたか」が分かる

## 💾 SQLiteって何？
軽量で使いやすいデータベースです。
- ファイル1つでデータベース全体を管理
- 設定が簡単で初心者向け
- スマホアプリでもよく使われる

## 📝 SQL文の種類

### DDL（Data Definition Language）- 構造を作る
テーブルやデータベースの「構造」を作ったり変更するSQL文

```sql
-- テーブルを作る
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE
);

-- テーブルを削除
DROP TABLE users;

-- テーブル構造を変更
ALTER TABLE users ADD COLUMN age INTEGER;
```

### DML（Data Manipulation Language）- データを操作する
テーブルの中の「データ」を追加・更新・削除・取得するSQL文

```sql
-- データを追加
INSERT INTO users (name, email) VALUES ('田中太郎', 'tanaka@example.com');

-- データを取得
SELECT * FROM users;
SELECT name, email FROM users WHERE age >= 20;

-- データを更新
UPDATE users SET age = 25 WHERE name = '田中太郎';

-- データを削除
DELETE FROM users WHERE id = 1;
```

## 🔄 マイグレーションとは？
データベースの設計図を管理する仕組みです。

### なぜ必要？
- チーム開発で全員が同じデータベース構造を使える
- 変更履歴が残る
- 間違えても元に戻せる

### Laravel でのマイグレーション

#### 1. マイグレーション作成
```bash
php artisan make:migration create_posts_table
```

#### 2. テーブル設計を書く
```php
// database/migrations/****_create_posts_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();                    // 自動増加するID
            $table->string('title');         // タイトル（文字列）
            $table->text('content');         // 内容（長い文字列）
            $table->timestamps();            // 作成日時・更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
```

#### 3. データベースに反映
```bash
php artisan migrate
```

## 🏗️ モデルとは？
データベースのテーブルを PHPで操作しやすくする仕組みです。

### モデルの作成
```bash
php artisan make:model Post
```

### モデルの使い方
```php
// app/Models/Post.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content'];
}
```

### コントローラーでモデルを使う
```php
// app/Http/Controllers/PostController.php
<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // 全ての投稿を取得
        $posts = Post::all();
        
        return view('posts.index', compact('posts'));
    }
    
    public function store()
    {
        // 新しい投稿を作成
        Post::create([
            'title' => 'はじめての投稿',
            'content' => 'こんにちは！'
        ]);
    }
}
```

## 🎯 実践：ブログ機能を作ってみよう

### 1. 投稿テーブル作成
```bash
php artisan make:migration create_posts_table
php artisan make:model Post
```

### 2. 投稿一覧ページ
```php
// routes/web.php
Route::get('/posts', [PostController::class, 'index']);
```

```html
<!-- resources/views/posts/index.blade.php -->
<h1>投稿一覧</h1>
@foreach($posts as $post)
    <div>
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>
        <small>{{ $post->created_at }}</small>
    </div>
@endforeach
```

## 💡 覚えておくと便利

### データベース確認コマンド
```bash
# テーブル一覧確認
php artisan tinker
>>> Schema::getTableListing()

# データ確認
>>> App\Models\Post::all()
```

### よくあるエラーと対処法
- **テーブルが存在しない** → `php artisan migrate` を実行
- **カラムが存在しない** → マイグレーションファイルを確認
- **データが保存されない** → モデルの `$fillable` を確認

## 🆘 困ったとき
1. エラーメッセージをよく読む
2. `php artisan migrate:status` でマイグレーション状況確認
3. `database/database.sqlite` ファイルが作成されているか確認