
## 各種パッケージのインストール


### VSCode

下記のURLからexeのインストーラーをダウンロードして、VSCodeのインストールを行う  
https://code.visualstudio.com/

下記の拡張機能も同時に入れる
- PHP Intelephense: https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client
- SQLite          : https://marketplace.visualstudio.com/items?itemName=qwtel.sqlite-viewer

### larave herd

herd経由でphp, composer, nodeのインストールをする  
https://herd.laravel.com/

### git

下記のURLからexeのインストーラーをダウンロードして、gitのインストールを行う  
https://git-scm.com/


## gitの認証

git-credential-managerが既に入っていることを確認する

```sh
$ git-credential-manager version
```

git-credential-manager経由での認証にする
```sh
$ git config --global credential.helper manager-core
```

自分の名前を認証する
```sh
$ git config --global user.email "hi-tamai@ceres-inc.jp"
$ git config --global user.name "hi-tamai"
```

git cloneなど、リモートブランチへの操作を行う

## 起動方法

```sh
# このrepostioryをclone
$ git clone XXXXX

# cloneしたレポジトリに移動
$ cd XXXXX

# phpとcomposerがインストールされていることを確認
$ composer -V
Composer version 2.8.10 2025-07-10 19:08:33
PHP version 8.4.10 (/Users/h-tamai/homebrew/Cellar/php/8.4.10/bin/php)
Run the "diagnose" command to get more detailed diagnostics output.

# 必要パッケージのインストール
$ composer i

# .envファイルをコピー
$ cp .env.example .env

# アプリケーション固有のkeyを発行
$ php artisan key:generate

# マイグレーションの実行（すべてyesでOK）
$ php artisan migrate

# アプリケーションの起動
# Let's get startedと表示が出ればOK
$ php artisan serve
```
