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
  - [このプロジェクトのフロントエンド構成](#このプロジェクトのフロントエンド構成)
    - [まず知っておきたい用語](#まず知っておきたい用語)
    - [調べ方・考え方の手順](#調べ方考え方の手順-1)
    - [このプロジェクトで分かったこと](#このプロジェクトで分かったこと-3)
      - [全体方式：SPA ではなく Blade によるサーバーサイド描画](#全体方式spa-ではなく-blade-によるサーバーサイド描画)
      - [ビルドツールと依存ライブラリ](#ビルドツールと依存ライブラリ)
      - [共通 JavaScript と CSS の構成](#共通-javascript-と-css-の構成)
      - [画面レイアウトの 2 系統](#画面レイアウトの-2-系統)
      - [各画面の役割](#各画面の役割)
      - [共通 UI と画面内インタラクション](#共通-ui-と画面内インタラクション)
      - [データの流れ](#データの流れ)
    - [注意点・改善のヒント](#注意点改善のヒント-1)
    - [一言まとめ](#一言まとめ-1)


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

## このプロジェクトのフロントエンド構成

### まず知っておきたい用語
- **フロントエンド**：ユーザーがブラウザで直接見て操作する部分のこと。HTML、CSS、JavaScript で作られる。
- **サーバーサイド描画（SSR）**：画面の HTML をサーバー側で組み立ててからブラウザに送る方式。対義語は SPA（後述）。
- **SPA（Single Page Application）**：React や Vue などを使い、ブラウザ側で画面を組み立てる方式。ページ遷移時にページ全体を再読み込みしない。
- **Blade テンプレート**：Laravel が提供するテンプレートエンジン。`.blade.php` という拡張子のファイルに HTML と PHP を混ぜて書き、サーバー側で HTML を生成する仕組み。
- **Vite（ヴィート）**：フロントエンドのビルドツール。CSS や JavaScript のファイルをまとめたり、開発中にファイル変更を即座にブラウザへ反映したりする。
- **エントリーポイント**：ビルドツールが「ここから処理を開始する」と認識する起点ファイルのこと。
- **Tailwind CSS**：あらかじめ用意されたクラス名（例: `text-red-500`、`mt-4`）を HTML に付けるだけでデザインできる CSS フレームワーク。
- **CDN（Content Delivery Network）**：外部サーバーからライブラリを読み込む方法。`<script src="https://...">` のように URL 指定で読み込む形。
- **Breeze（ブリーズ）**：Laravel 公式のスターターキット。ログイン、ユーザー登録、パスワードリセットなどの認証まわりの画面とロジックを一式生成してくれる。
- **Alpine.js**：HTML に `x-data` や `@click` などの属性を書くだけで簡易的なインタラクションを実装できる軽量 JavaScript フレームワーク。Breeze が生成する画面で使われることがある。
- **レイアウト**：ヘッダー、フッター、ナビゲーションなど複数の画面で共通する外枠部分を定義したテンプレートのこと。各画面はレイアウトの中に自分の内容をはめ込む形で表示される。
- **コンポーネント**：ボタンやカードなど、繰り返し使う UI パーツを 1 つのファイルにまとめたもの。同じパーツを複数の画面で使い回せる。
- **ボトムシート**：画面下部からスライドして出てくるパネル型の UI。詳細情報やメニューを表示するためによく使われる。
- **WBS（Work Breakdown Structure）**：作業を細かく分解して、時間軸に沿って並べた一覧表。プロジェクト管理でよく使われる形式。
- **イベントリスナー**：「ボタンがクリックされたら」「入力欄の値が変わったら」のように、特定の操作（イベント）が起きたときに実行する処理を登録する仕組み。

### 調べ方・考え方の手順
- まず package.json（プロジェクトが使っている JavaScript ライブラリの一覧ファイル）と vite.config.js（Vite の設定ファイル）を見て、フロントエンドのビルドツール、使っているライブラリ、エントリーポイントを確認する。
- 次に resources/js と resources/css を見て、全画面で共通して使われる JavaScript や CSS がどこまであるかを把握する。
- その後 routes/web.php（URL と処理の対応を書くファイル）を見て、実際に使われている URL と画面の対応を確認する。ここを見ないと、存在する Blade ファイルのうちどれが実際にユーザーに表示される画面なのか判断しづらい。
- 次に resources/views 配下の Blade ファイルを見て、共通レイアウト、画面ごとのビュー、再利用するコンポーネントを分けて把握する。
- さらに `<script>` タグ、`onclick` 属性、`x-data` 属性などをファイル横断で検索して、画面のインタラクション（ユーザー操作に対する動き）が共通の JS ファイルにあるのか、各 Blade ファイルの中にバラバラに書かれているのかを確認する。
- 最後に対応する Controller（画面を返す処理を書くファイル）を見て、その Blade が実際に返されているか、画面にどんなデータが渡されているかを確認する。

### このプロジェクトで分かったこと

#### 全体方式：SPA ではなく Blade によるサーバーサイド描画
- このプロジェクトは React や Vue のような SPA ではない。Laravel の Blade テンプレートを使って、サーバー側で HTML を組み立ててブラウザに送る方式になっている。
- つまり、ユーザーがリンクをクリックするたびにサーバーに問い合わせが行き、サーバーが新しい HTML を返す形である。

#### ビルドツールと依存ライブラリ
- ビルドツールは Vite で、エントリーポイント（Vite が処理を始める起点）は resources/css/app.css と resources/js/app.js の 2 つ。
- package.json を見ると、主に使っているライブラリは以下の通り。
  - **vite**：ビルドツール本体。
  - **laravel-vite-plugin**：Laravel と Vite を連携させるプラグイン。
  - **tailwindcss** / **@tailwindcss/vite**：Tailwind CSS 本体とその Vite 用プラグイン。
  - **axios**：サーバーとの HTTP 通信を簡単に書けるライブラリ。API にデータを送受信するときに使う。

#### 共通 JavaScript と CSS の構成
- resources/js/app.js は bootstrap.js を読み込むだけの薄い構成。全画面で共有する JavaScript はほとんどない。
- bootstrap.js では axios の初期設定だけが行われており、画面の見た目や操作に関するロジックはここにはない。
- CSS は resources/css/app.css に Tailwind v4 の設定がある。しかし一方で、主要画面では Tailwind の CDN（外部 URL からの読み込み）やインラインスタイル（HTML タグに直接 `style="..."` と書くやり方）も使われている。
- つまり、「Vite で管理されたアセット（CSS/JS ファイル）」と「Blade ファイルの中に直接書かれたスタイルやスクリプト」が混在している。

#### 画面レイアウトの 2 系統
- このプロジェクトには画面の外枠（レイアウト）が大きく 2 種類ある。
- **Breeze 標準系**：layouts/app.blade.php、layouts/guest.blade.php、および auth フォルダ配下のビュー群。これは Laravel Breeze が自動生成したログイン・ユーザー登録まわりの画面で使われるレイアウト。
- **独自アプリ画面系**：components/layout.blade.php を共通レイアウトとして使い、welcome.blade.php、newGoal.blade.php、milestonesFlow.blade.php、milestonesWBS.blade.php などのアプリ独自の画面を描画する系統。
- routes/web.php と各 Controller を確認すると、**ユーザーが実際に使う主要な画面は独自アプリ画面系の方**である。
- ログイン画面も、Breeze が自動生成した auth/login.blade.php ではなく、LoginController から独自の login.blade.php が返されている。
- つまり、Breeze 標準画面のファイルはプロジェクト内に残っているが、実際にユーザーが触る画面ではないので、分けて考える必要がある。

#### 各画面の役割
- **welcome.blade.php**：目標一覧画面（ホーム画面）。目標ごとの進捗率、残り日数、今月の実績などを表示する。
- **newGoal.blade.php**：目標作成フォーム。3 ステップ（入力 → カテゴリー選択 → 確認）を 1 つの画面内で JavaScript によるステップ切り替えで実現している。ページ遷移はせず、表示を切り替えるだけ。
- **milestonesFlow.blade.php**：マイルストーン（目標達成までの中間ステップ）を縦に並べて表示する画面。チェックボックスでの達成切り替え、進捗率の更新、詳細を表示するボトムシート、達成時の演出アニメーションなどを画面内の JavaScript で実装している。
- **milestonesWBS.blade.php**：マイルストーンを時間軸に沿ってタイムライン形式で表示する WBS 画面。横スクロール、今日の日付位置の表示、行の高さ同期などを JavaScript で制御している。

#### 共通 UI と画面内インタラクション
- components/layout.blade.php（共通レイアウト）には、独自ヘッダー、下部ナビゲーションバー、ボトムシート、通知トースト（画面上部に一時的に表示される通知メッセージ）、ユーザーメニューなど、どの画面でも共通で使う UI パーツがまとめて書かれている。
- 画面上のインタラクション（ボタンを押したら何が起きるか等）は、React や Vue のようなフレームワークではなく、**Blade ファイル内に直接書かれた `<script>` タグと `onclick="..."` 属性による素の JavaScript** が中心になっている。
- 一部の Breeze が生成したコンポーネントには Alpine.js の `x-data` 属性などが見られるが、独自アプリ画面では Alpine.js は主要な手段ではない。

#### データの流れ
- Controller がデータベースからデータを取得・加工して Blade テンプレートに渡す → Blade がそのデータを使って HTML を生成する → ブラウザに表示された後、ユーザー操作に応じた UI の変化だけをフロント側の JavaScript が担当する、という流れになっている。

### 注意点・改善のヒント
- フロントエンドを調べるときは、存在するビューファイルを全部同じ重みで見るのではなく、まず routes/web.php と Controller を見て「実際にユーザーに使われている画面はどれか」を特定した方がよい。ファイルが存在しても、どこからもアクセスされていなければ現役の画面ではない。
- このプロジェクトでは Breeze が自動生成した画面群と、独自に作った画面群が両方残っているので、初見だと「どの画面が本番で使われているのか」が分かりづらい。
- Tailwind CSS を Vite 経由（ビルドして使う方法）でも使い、さらに CDN（外部 URL から直接読み込む方法）でも使っている。スタイルの定義場所が分散しているため、今後の保守を考えるならどちらか一方に寄せた方が分かりやすい。
- 全画面共通の JavaScript がほとんどなく、画面ごとの処理が各 Blade ファイルの `<script>` タグ内に大きく書かれている。画面が増えると似たような処理をコピペすることになりやすく、修正漏れやバグの原因になる。
- `onclick="..."` 属性を使って直接 HTML にクリック時の処理を書く方式は、「この要素を押すと何が起きるか」が HTML 上で見えやすい利点がある。しかし、処理が HTML のあちこちに散らばるため管理しづらくなる。改善するなら、JavaScript ファイル側でイベントリスナー（`addEventListener`）を使って処理を登録する形に整理し、共通で使う関数を別ファイルに分離すると保守性が上がる。
- 共通レイアウトに通知、ボトムシート、メニューの制御がまとまっているのは良い設計だが、今後さらに機能が増えるなら、これらのロジックを resources/js フォルダ内の JavaScript ファイルに移して、HTML（見た目）と JavaScript（動き）の役割を明確に分けた方がよい。
- 調査の順番としては、「ビルド設定 → 共通 CSS/JS → routes → views → Controller」の順で見ると、フロントエンド全体像を短時間でつかみやすい。

### 一言まとめ
- このプロジェクトのフロントエンドは、Blade テンプレートでサーバー側が HTML を生成し、Tailwind CSS でデザインする構成になっている。画面ごとの動きを制御する JavaScript は共通ファイルにまとまっておらず、各 Blade ファイルの中にそれぞれ書かれている。