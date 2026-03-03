# フリマアプリ（coachtech 模擬案件）

Laravelを使用したフリマアプリです。
ユーザー登録、商品出品、購入、いいね、コメント、カテゴリー分類などの基本機能を実装しています。

# アプリケーション概要

本アプリは、ユーザーが商品を出品・購入できるフリマアプリです。
出品された商品に対して「いいね」や「コメント」が可能で、
商品は複数カテゴリに紐づけて管理できます。

また、購入希望者と出品者が商品ごとに取引チャットを行うことができ、
取引完了後には相互評価を行う仕組みを実装しています。

## 環境構築

Dockerビルド

git clone git@github.com:yuichihomma/coachtech-flea-app.git
cd coachtech-flea-app
docker-compose up -d --build

Laravel環境構築

cp .env.example src/.env
docker-compose exec php bash
cd /var/www/html
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed

※ `src/.env` のDB接続は以下を設定してください。
- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE=laravel_db`
- `DB_USERNAME=laravel_user`
- `DB_PASSWORD=laravel_pass`

## 使用技術

・PHP 8.x
・Laravel 8.x
・MySQL 8.x
・Nginx
・Docker / Docker Compose
・JavaScript
・CSS

## ER図
![ER図]![alt text](<docs/new er-diagram.png>)

## 取引チャット機能

- 商品ごとに購入希望者と出品者の間でチャットルームを作成
- 同一商品に対し、同一購入希望者は1つのchat_roomのみ作成可能（unique制約）
- 取引ステータスは以下の流れで管理
  - trading（取引中）
  - rating（評価待ち）
  - completed（完了）
- 取引完了時にメール通知を送信
- 取引後は1〜5の整数で相互評価可能
- 同一チャット内での多重評価を防ぐため、(chat_room_id, rater_id) にunique制約を設定

## ダミーデータについて

本アプリでは、`php artisan db:seed` により以下のダミーデータを作成しています。

### ユーザー一覧

- `User::factory()->create()` により、ランダムなユーザーが1件作成されます
- パスワードは `password` です
- 作成されるユーザーの名前・メールアドレスは実行ごとに変わります

### 商品データ

商品データは10件作成され、すべて上記ユーザー（`user_id = 1`）に紐づきます。

- 商品名:
  - 腕時計
  - HDD
  - 玉ねぎ3束
  - 革靴
  - ノートPC
  - マイク
  - ショルダーバック
  - タンブラー
  - コーヒーミル
  - メイクセット
- 各商品は価格・説明・画像URL・コンディション情報を保持します
- `is_sold` はSeederで指定していないため、すべて未購入（`0`）で登録されます

## 開発環境URL

・トップページ：http://localhost/
・会員登録：http://localhost/register
・ログイン:http://localhost/login
・phpMyAdmin:http://localhost:8081/
