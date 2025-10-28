# 工数管理ツール

社内向けの工数（作業時間）を集計するために CakePHP で構築された Web アプリケーションです。ユーザーごとの日報入力から案件別の時間配分、残業時間の算出までを一つの画面で完結できるように設計されています。

## 主な機能
- ユーザーと日付を指定して作業時間を入力・編集
- 案件（Matter）ごとの稼働時間集計
- 残業時間の自動計算
- 作業内容のメモ保存

## 技術スタック
- PHP 5 系
- CakePHP 2 系
- MySQL などの RDBMS

## セットアップのヒント
1. `app/Config/database.php.default` をコピーして `database.php` を用意し、接続情報を設定します。
2. `app/Config/core.php` の `Security.salt` と `Security.cipherSeed` を環境に合わせて変更します。
3. `app/Console/cake schema create` を使って必要なテーブルを作成します。
4. Apache などの Web サーバーから `app/webroot` をドキュメントルートとして参照するように設定します。

## 補足
このリポジトリには CakePHP の公式スケルトンが同梱されているため、フレームワークのドキュメントも一緒に配置されています。アプリケーション固有のコードは `app/Controller` や `app/Model`、`app/View` 配下を参照してください。
