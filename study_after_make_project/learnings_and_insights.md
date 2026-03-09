## LaravelプロジェクトでDB構成を調べる流れ

### 1. 最初に接続先を確認する
- 最初に見るのは config/database.php と .env。
- ここで、どのDBを使っているか、接続先はどこか、既定値は何かを確認する。
- このプロジェクトでは DB_CONNECTION=sqlite になっており、SQLite を使っている。
- DBファイルは database/database.sqlite にある。

### 2. migrations を見てテーブル一覧を把握する
- database/migrations を見ると、どんなテーブルが存在するか分かる。
- Laravel標準の管理用テーブルと、アプリ独自の業務テーブルを分けて考えると整理しやすい。

#### このプロジェクトで確認できた主なテーブル
- users
- password_reset_tokens
- sessions
- cache
- cache_locks
- jobs
- job_batches
- failed_jobs
- migrations
- goals
- milestones

### 3. models を見て、アプリがそのテーブルをどう扱うか確認する
- app/Models を見る。
- fillable があれば、どの値をまとめて保存しようとしているか分かる。
- casts があれば、日付や真偽値などの扱いが分かる。
- belongsTo や hasMany があればテーブル同士の関係が明確になる。

#### このプロジェクトで分かったこと
- User モデルは認証用の標準モデル。
- Goal モデルは goals テーブルを扱う。
- Milestone モデルは milestones テーブルを扱う。
- ただし Goal と Milestone には hasMany や belongsTo がまだ定義されていない。
- つまり、テーブル同士の関係はコントローラ側の where 句でつないでいる。

### 4. controllers を見て、実際のデータの流れを追う
- テーブル定義だけでは「何のためのデータか」が見えにくい。
- app/Http/Controllers を見て、create、find、where などを追う。
- ここで「どの画面で」「どのテーブルを」「どう使っているか」が分かる。

#### このプロジェクトでのデータの流れ
1. ユーザーが新しい目標を入力する。
2. NewgoalController で goals テーブルに1件保存する。
3. その目標に対して milestones を複数自動生成して milestones テーブルに保存する。
4. UserGoalListController で、ログインユーザーの goals と milestones を集計して一覧表示する。
5. MilestonesFlowController と MilestonesWBSController で、特定の目標に紐づく milestones を表示する。

### 5. routes を見て、どの画面がどの処理を呼んでいるか確認する
- routes/web.php を見ると、URL とコントローラの対応が分かる。
- どの画面がDBアクセスを起こすかを追う時に役立つ。

#### このプロジェクトでの対応例
- / は UserGoalListController
- /new は NewgoalController
- /milestones-flow/{goal_id} は MilestonesFlowController
- /milestones-wbs/{goal_id} は MilestonesWBSController

### 6. seeders と factories を見て、想定データを把握する
- database/seeders と database/factories を見る。
- 開発者がどんなデータ構造を想定しているかが分かる。
- 実運用のデータでなくても、設計意図を読む材料になる。

#### このプロジェクトで分かったこと
- DatabaseSeeder でテストユーザーを作成している。
- GoalSeeder で goals を複数作成している。
- MilestoneSeeder で、各 goal に対して milestone を複数作成している。
- つまり設計意図としては、1ユーザーが複数の goal を持ち、1つの goal が複数の milestone を持つ形になっている。

## このプロジェクトのDB構成まとめ

### 使用しているDB
- SQLite
- 保存先は database/database.sqlite

### 業務データとして重要なテーブル

#### users
- ユーザー情報を管理する。
- ログイン、認証、ユーザー識別の基点になる。

#### goals
- ユーザーごとの目標を保存する。
- 主なカラムは user_id、category、name。

#### milestones
- 各目標を達成するための中間ステップを保存する。
- 主なカラムは goal_id、name、description、startDate、endDate、achieved。

### Laravel管理用のテーブル

#### sessions
- セッション情報をDBに保存する。
- このプロジェクトは SESSION_DRIVER=database なので使われる。

#### cache / cache_locks
- キャッシュとキャッシュロックをDBに保存する。
- このプロジェクトは CACHE_STORE=database なので使われる。

#### jobs / job_batches / failed_jobs
- キュー処理と失敗ジョブの管理を行う。
- このプロジェクトは QUEUE_CONNECTION=database なので、ジョブを使えばDBに入る。

#### password_reset_tokens
- パスワードリセット用トークンを保存する。

#### migrations
- 実行済みマイグレーションの履歴を管理する。

## テーブル同士の関係
- users 1 : 多 goals
- goals 1 : 多 milestones

## このプロジェクトを見る上での注意点
- goals.user_id と milestones.goal_id はある。
- ただし migration 上の外部キー制約はコメントアウトされている。
- つまりDBレベルで厳密に参照整合性を守っているわけではない。
- 現状はアプリケーション側の実装で整合性を保っている。

## 今後、自分でDB構成を調べる時のチェック順
1. config/database.php と .env を見る。
2. database/migrations を見てテーブル一覧を把握する。
3. app/Models を見て fillable、casts、relation を確認する。
4. app/Http/Controllers を見て create、find、where を追う。
5. routes/web.php で画面と処理の対応を確認する。
6. database/seeders と database/factories を見て想定データを把握する。

## 一言でまとめる
- このプロジェクトは、SQLite 上に users、goals、milestones を中心とした業務データを持ち、さらに sessions、cache、jobs などの Laravel 管理用データも同じDBに保存する構成になっている。