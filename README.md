# 画像付き掲示板（PHP + Nginx + MySQL on Docker）

シンプルな画像付きBBS。投稿本文と画像を保存し、一覧表示します。  
和風テイストのCSSと**スマホ自動
---
## 1. docker および docker compose のインストール方法（Amazon Linux 2023 例）

```bash
sudo dnf update -y
sudo yum install git -y

sudo yum install -y docker
sudo systemctl start docker
sudo systemctl enable docker

sudo usermod -a -G docker ec2-user

# まだなら git を入れる
sudo dnf install -y git

# SSH鍵をEC2で作成（３回Enter連打でOK）
ssh-keygen -t ed25519

# 公開鍵を表示してコピー
cat ~/.ssh/id_ed25519.pub


コピーした内容を GitHub → Settings → SSH and GPG keys → New SSH key に貼り付けて保存

ssh -T git@github.com   # "Hi <ユーザー名>!" が出たらいい

git clone git@github.com:akito64/kadai.git
cd kadai




Dockerデーモン起動
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
newgrp docker
docker ps   # エラーが出なければOK


Docker Compose v2を入れる
sudo mkdir -p /usr/local/lib/docker/cli-plugins
sudo curl -SL "https://github.com/docker/compose/releases/download/${VER}/docker-compose-linux-${BIN}" \
  -o /usr/local/lib/docker/cli-plugins/docker-compose
sudo chmod +x /usr/local/lib/docker/cli-plugins/docker-compose

docker compose version


次に.envを作成
cat > .env <<'EOF'
MYSQL_ROOT_PASSWORD=changeme_root
MYSQL_DATABASE=example_db
MYSQL_USER=bbs_user
MYSQL_PASSWORD=changeme_app
EOF

アップロード先を用意する
mkdir -p public/uploads
chmod 777 public/uploads


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
