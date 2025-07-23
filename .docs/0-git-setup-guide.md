# Git初期設定・認証確認ガイド

## 🎯 このガイドの目的
GitHubアカウントでGit認証を設定し、プッシュできることを確認する

## 🛠️ Step 1: Git基本設定

### 1-1. ユーザー情報設定
```bash
# 自分の名前を設定（GitHubの表示名と同じにする）
git config --global user.name "田中太郎"

# 自分のメールアドレスを設定（GitHubのメールアドレスと同じにする）
git config --global user.email "tanaka@example.com"
```

### 1-2. 設定確認
```bash
# 設定が正しいか確認
git config --global user.name
git config --global user.email
```

## 🔐 Step 2: Git Credential Manager設定

### 2-1. Credential Manager確認
```bash
# Git Credential Managerがインストールされているか確認
git config --global credential.helper
```
→ `manager` または `manager-core` と表示されればOK

### 2-2. Credential Manager設定（必要に応じて）
```bash
# もし設定されていない場合
git config --global credential.helper manager
```

## 📂 Step 3: リポジトリのセットアップ

### 3-1. リポジトリ状態確認
```bash
# 現在のブランチとリモート設定を確認
git status
git remote -v
```

## 🚀 Step 4: ブランチ作成・認証確認テスト

### 4-1. 新しいブランチを作成・移動
```bash
# 自分の名前でブランチを作成（例：tanaka-test）
git checkout -b あなたの名前-test
```

### 4-2. ファイル変更・コミット
```bash
# 新しいファイルを作成（自分の名前で）
echo "# あなたの名前のテストファイル" > あなたの名前-test.md
echo "Git認証テストを実行中" >> あなたの名前-test.md

# ファイルをステージに追加
git add あなたの名前-test.md

# コミット
git commit -m "あなたの名前: 認証テスト用ファイルを追加"
```

### 4-3. 初回プッシュ（認証が発生）
```bash
# 新しいブランチをプッシュ（認証が発生）
git push origin あなたの名前-test
```

**この時点で以下が発生します：**
1. ブラウザが自動で開く
2. GitHubのログイン画面が表示される
3. ユーザー名・パスワードを入力
4. 2段階認証がある場合は入力
5. 「Authorize Git Credential Manager」をクリック
6. 認証完了後、プッシュが実行される

### 4-4. 認証成功の確認
プッシュが成功すると以下のようなメッセージが表示されます：
```
Enumerating objects: 3, done.
Counting objects: 100% (3/3), done.
Writing objects: 100% (3/3), 245 bytes | 245.00 KiB/s, done.
Total 3 (delta 0), reused 0 (delta 0)
To https://github.com/組織名/リポジトリ名.git
 * [new branch]      あなたの名前-test -> あなたの名前-test
```

## 🔄 Step 5: 追加変更テスト（認証済み確認）

### 5-1. ファイルに追加変更
```bash
# 既存ファイルに内容を追加
echo "追加のテスト内容" >> あなたの名前-test.md
echo "日時: $(date)" >> あなたの名前-test.md
```

### 5-2. 追加変更をコミット・プッシュ
```bash
# 変更をステージに追加
git add あなたの名前-test.md

# コミット
git commit -m "あなたの名前: 追加テスト内容を記録"

# プッシュ（今度は認証なしでプッシュできるはず）
git push origin あなたの名前-test
```

## ✅ Step 6: 動作確認

### 6-1. GitHubで確認
1. GitHubのリポジトリページを開く
2. 「あなたの名前-test」ブランチが存在することを確認
3. ブランチ内にあなたが作成したファイルがあることを確認
4. コミット履歴で自分の変更が記録されていることを確認

### 6-2. ローカルで確認
```bash
# 現在のブランチ確認
git branch

# mainブランチに戻る
git checkout main

# 全ブランチ一覧確認（リモートも含む）
git branch -a
```

## 🎉 認証設定完了！

以下が確認できれば認証設定は完璧です：
- ✅ Git Credential Managerでの認証が成功
- ✅ mainブランチへのプッシュが成功
- ✅ 新しいブランチの作成・プッシュが成功
- ✅ GitHubでファイル・ブランチが確認できる

## 🆘 トラブルシューティング

### 認証に失敗する場合
```bash
# 保存されている認証情報をクリア
git config --global --unset credential.helper
# または
git credential-manager-core erase

# 再度設定
git config --global credential.helper manager
```

### プッシュに失敗する場合
1. GitHubのユーザー名・リポジトリ名が正しいか確認
2. リポジトリがPrivateの場合、アクセス権限があるか確認
3. 2段階認証が有効な場合、Personal Access Tokenの設定が必要な場合あり

## 🧹 後処理
```bash
# 作業完了後、mainブランチに戻る
git checkout main

# テストブランチはそのまま残しておく（講師確認用）
```

**注意：** テストブランチは講師の確認が済むまで削除しないでください。

---

**この手順が完了すれば、以降のGit操作で認証を気にする必要はありません！**
