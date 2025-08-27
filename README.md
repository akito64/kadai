# 画像付き掲示板（PHP + Nginx + MySQL on Docker）

シンプルな画像付きBBS。投稿本文と画像を保存し、一覧表示します。  
和風テイストのCSSと**スマホ自動
---
## 1. docker および docker compose のインストール方法（Amazon Linux 2023 例）

```bash
sudo dnf update -y
sudo dnf install -y docker docker-compose-plugin git
sudo systemctl enable --now docker
sudo usermod -aG docker ec2-user
newgrp docker            # グループ反映（再ログインでも可）
docker compose version   # 動作確認

# ビルド
docker compose build

# 起動
docker compose up

# 起動確認
docker compose ps

テーブルの作成方法
MySQL コンテナに入ってテーブルを作成

docker compose exec mysql mysql example_db

sql文を追加
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

## 0. ソース取得（git clone）


### SSH（公開鍵をGitHubに登録済みの人向け）
```bash
git clone git@github.com:akito64/kadai.git
cd kadai
