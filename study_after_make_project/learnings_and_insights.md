# プロジェクト作成後に得た学び
見出し2の形で各学びに章をつけて管理して増やしていく。

- [プロジェクト作成後に得た学び](#プロジェクト作成後に得た学び)
  - [LaravelプロジェクトでDB構成を調べる流れ](#laravelプロジェクトでdb構成を調べる流れ)
    - [1. 最初に接続先を確認する](#1-最初に接続先を確認する)
    - [2. migrations を見てテーブル一覧を把握する](#2-migrations-を見てテーブル一覧を把握する)
      - [このプロジェクトで確認できた主なテーブル](#このプロジェクトで確認できた主なテーブル)
    - [3. models を見て、アプリがそのテーブルをどう扱うか確認する](#3-models-を見てアプリがそのテーブルをどう扱うか確認する)
      - [このプロジェクトで分かったこと](#このプロジェクトで分かったこと)
    - [4. controllers を見て、実際のデータの流れを追う](#4-controllers-を見て実際のデータの流れを追う)
      - [このプロジェクトでのデータの流れ](#このプロジェクトでのデータの流れ)
    - [5. routes を見て、どの画面がどの処理を呼んでいるか確認する](#5-routes-を見てどの画面がどの処理を呼んでいるか確認する)
      - [このプロジェクトでの対応例](#このプロジェクトでの対応例)
    - [6. seeders と factories を見て、想定データを把握する](#6-seeders-と-factories-を見て想定データを把握する)
      - [このプロジェクトで分かったこと](#このプロジェクトで分かったこと-1)
  - [このプロジェクトのDB構成まとめ](#このプロジェクトのdb構成まとめ)
    - [使用しているDB](#使用しているdb)
    - [業務データとして重要なテーブル](#業務データとして重要なテーブル)
      - [users](#users)
      - [goals](#goals)
      - [milestones](#milestones)
    - [Laravel管理用のテーブル](#laravel管理用のテーブル)
      - [sessions](#sessions)
      - [cache / cache\_locks](#cache--cache_locks)
      - [jobs / job\_batches / failed\_jobs](#jobs--job_batches--failed_jobs)
      - [password\_reset\_tokens](#password_reset_tokens)
      - [migrations](#migrations)
  - [テーブル同士の関係](#テーブル同士の関係)
  - [このプロジェクトを見る上での注意点](#このプロジェクトを見る上での注意点)
  - [今後、自分でDB構成を調べる時のチェック順](#今後自分でdb構成を調べる時のチェック順)
  - [一言でまとめる](#一言でまとめる)
  - [このプロジェクトのデータベース設計の改善点](#このプロジェクトのデータベース設計の改善点)
    - [調べ方・考え方の手順](#調べ方考え方の手順)
    - [このプロジェクトで分かったこと](#このプロジェクトで分かったこと-2)
    - [注意点・改善のヒント](#注意点改善のヒント)
    - [一言まとめ](#一言まとめ)


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

## このプロジェクトのデータベース設計の改善点

### 調べ方・考え方の手順
- まず database/migrations を見て、外部キー制約、インデックス、カラム型、デフォルト値があるかを確認する。
- 次に app/Models を見て、relation と casts が定義されているかを確認する。
- その後 app/Http/Controllers を見て、複数テーブルへの保存が1回の処理で行われている箇所を探し、途中失敗でデータが壊れないかを考える。
- routes と画面の流れも合わせて見て、どの画面でどの検索条件が多く使われるかを確認する。
- 改善点を考えるときは、「変なデータが入らないか」「途中失敗で中途半端な状態にならないか」「データ件数が増えても遅くなりにくいか」の順で考える。

### このプロジェクトで分かったこと
- goals テーブルの user_id と milestones テーブルの goal_id は、現状どちらも整数カラムとして定義されている。
- users と goals、goals と milestones のつながりを守る外部キー制約はコメントアウトされており、DB自体は参照関係を強制していない。
- NewgoalController では、先に goal を1件保存し、その後に milestone を複数件保存している。
- つまり保存途中で失敗すると、goal だけ残るなどの中途半端なデータが起こりうる。
- UserGoalListController、MilestonesFlowController、MilestonesWBSController では user_id や goal_id での検索が多く、今後データが増えると検索速度の影響を受けやすい。
- category は文字列で保存されており、値の種類をDB側では制限していない。
- milestones の startDate、endDate、achieved は使われているが、モデル側で日付型や真偽値としての扱いが明示されていない。
- Goal モデルと Milestone モデルには relation がなく、テーブル同士の関係を controller 側の where 句でつないでいる。

### 注意点・改善のヒント
- 外部キー制約とは、「この goal_id は必ず存在する goals.id を指す」のようなルールをDBに守らせる仕組み。これがないと、親のデータが消えたのに子のデータだけ残ることがある。
- まず優先して改善したいのは、goals.user_id と milestones.goal_id に外部キー制約を付けること。これにより参照整合性をDBレベルで守りやすくなる。
- トランザクションとは、「まとめて全部成功するか、途中で失敗したら全部取り消す」仕組み。goal と milestone を一連で保存する処理は、本来これでまとめるのが安全。
- インデックスとは、検索しやすくするための目次のような仕組み。user_id と goal_id は検索に多く使うので、件数が増える前に意識しておく価値がある。
- category は自由度が高いままだと表記ゆれが起きやすい。たとえば study と Study が別物として保存されると集計しづらいので、入力候補の固定や参照用テーブルの導入が改善案になる。
- カラム型も見直し候補がある。短い名前なら text より string の方が意図が分かりやすく、achieved に初期値 false を持たせると扱いが安定する。
- startDate や endDate のような名前は動くが、一般的には start_date、end_date のように命名規則をそろえると保守しやすい。
- relation はテーブル同士の関係をモデルに表す定義。これがないと、取得処理が各 controller に散らばりやすく、後で直しにくくなる。
- casts はDBの値をアプリ側で扱いやすい型に変換する設定。日付や真偽値の扱いをモデルに寄せると、controller ごとの変換処理が減って設計が安定する。
- 優先順位としては、外部キー制約、トランザクション、インデックスの順に考えると、初学者でも改善の意味を理解しやすい。

### 一言まとめ
- このプロジェクトのDB改善で最も大事なのは、テーブル同士の関係と保存処理の安全性をDBとアプリの両方で守り、データが増えても破綻しにくい設計に近づけること。