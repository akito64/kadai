<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

if (isset($_POST['body'])) {
  // POSTで送られてくるフォームパラメータ body がある場合

  $image_filename = null;
  if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    // アップロードされた画像がある場合
    if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1) {
      // アップロードされたものが画像ではなかった場合処理を強制的に終了
      header("HTTP/1.1 302 Found");
      header("Location: ./bbsimagetest.php");
      return;
    }

    // 元のファイル名から拡張子を取得
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    // 新しいファイル名（重複防止に時間+乱数）
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }

  // insertする
  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body, image_filename) VALUES (:body, :image_filename)");
  $insert_sth->execute([
    ':body' => $_POST['body'],
    ':image_filename' => $image_filename,
  ]);

  // 処理が終わったらリダイレクトする
  header("HTTP/1.1 302 Found");
  header("Location: ./bbsimagetest.php");
  return;
}

// いままで保存してきたものを取得
$select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
$select_sth->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- スマホ対応 -->
<title>画像付き掲示板</title>
<style>
  :root{
    --bg:#f7f4ea;       /* 和紙ベース */
    --ink:#1a1a1a;      /* 墨色 */
    --accent:#b33a3a;   /* 朱色 */
    --gold:#b48a1f;     /* 金茶 */
  }
  *{ box-sizing:border-box; }
  html,body{ height:100%; }
  body{
    margin:0;
    color:var(--ink);
    font-family:"Yu Mincho","Hiragino Mincho ProN","Hiragino Mincho Pro","Noto Serif JP","MS PMincho",serif;
    line-height:1.7;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(0,0,0,.03), transparent 40%),
      radial-gradient(800px 400px at 120% 120%, rgba(0,0,0,.04), transparent 40%),
      var(--bg);
  }
  .container{
    max-width:920px;
    margin:0 auto;
    padding:clamp(12px,2vw,24px);
  }
  header.site{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:16px;
  }
  .kamon{
    width:42px;height:42px;border-radius:999px;
    background:conic-gradient(from 0deg, var(--accent) 0 25%, transparent 25% 50%, var(--accent) 50% 75%, transparent 75% 100%);
    border:2px solid var(--accent);
    box-shadow:inset 0 0 0 3px #fff, 0 1px 4px rgba(0,0,0,.15);
  }
  .title{
    font-size:clamp(20px,4vw,28px);
    letter-spacing:.1em;
  }

  .paper{
    background:#fffef8;
    border:1px solid #e6e0c7;
    border-radius:14px;
    padding:clamp(12px,2vw,18px);
    box-shadow:0 10px 20px rgba(0,0,0,.05),
               0 1px 0 rgba(255,255,255,.8) inset,
               0 0 0 6px rgba(180,138,31,0.08);
    position:relative;
  }
  .paper::before{
    content:"";
    position:absolute; inset:0;
    border-radius:14px;
    border:1px dashed rgba(180,138,31,.5);
    pointer-events:none;
  }

  form.bbs{ display:grid; gap:12px; }
  textarea[name="body"]{
    width:100%; min-height:7rem;
    padding:10px 12px;
    font:inherit; font-size:1rem;
    border:1px solid #d8cfb0; border-radius:10px;
    background:#fffdf7;
  }
  input[type="file"]{ display:block; width:100%; }

  .btn{
    display:inline-block;
    padding:10px 18px;
    border-radius:999px;
    border:1px solid var(--accent);
    background:linear-gradient(#ffffff,#fff8f6);
    color:var(--accent); font-weight:600; letter-spacing:.08em;
    cursor:pointer;
    transition:transform .02s ease, box-shadow .2s ease, background .2s ease;
    box-shadow:0 2px 0 var(--accent), 0 6px 16px rgba(179,58,58,.15);
  }
  .btn:hover{ transform:translateY(-1px); }
  .btn:active{ transform:translateY(0); box-shadow:0 1px 0 var(--accent); }

  hr.sep{
    border:none; height:1px; margin:24px 0;
    background:linear-gradient(to right, transparent, rgba(0,0,0,.2), transparent);
    opacity:.3;
  }

  dl.entry{
    display:grid;
    grid-template-columns:5rem 1fr;
    gap:.25rem .75rem;
    padding:12px;
    margin:0 0 16px 0;
    border-bottom:1px solid rgba(0,0,0,.08);
  }
  dl.entry dt{ color:#6b5e3c; font-weight:600; }
  dl.entry dd{ margin:0; }
  .entry img{
    max-height:12em; width:auto; max-width:100%;
    border-radius:8px; border:1px solid #e6e0c7;
  }

  /* モバイル最適化 */
  @media (max-width:600px){
    dl.entry{ grid-template-columns:4.5rem 1fr; }
    .entry img{ max-height:9em; }
  }
</style>
</head>
<body>
  <div class="container">
    <header class="site">
      <div class="kamon" aria-hidden="true"></div>
      <h1 class="title">画像付き掲示板</h1>
    </header>

    <section class="paper">
      <!-- フォームのPOST先はこのファイル自身にする -->
      <form class="bbs" method="POST" action="./bbsimagetest.php" enctype="multipart/form-data">
        <textarea name="body" required placeholder="本文を入力してください"></textarea>
        <div>
          <input type="file" accept="image/*" name="image" id="imageInput">
        </div>
        <button class="btn" type="submit">送信</button>
      </form>
    </section>

    <hr class="sep">

    <?php foreach($select_sth as $entry): ?>
      <dl class="entry">
        <dt>ID</dt>
        <dd><?= $entry['id'] ?></dd>
        <dt>日時</dt>
        <dd><?= $entry['created_at'] ?></dd>
        <dt>内容</dt>
        <dd>
          <?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?>
          <?php if(!empty($entry['image_filename'])): // 画像がある場合は img 要素を使って表示 ?>
          <div>
            <img src="/image/<?= $entry['image_filename'] ?>" alt="">
          </div>
          <?php endif; ?>
        </dd>
      </dl>
    <?php endforeach ?>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const imageInput = document.getElementById("imageInput");
    if (!imageInput) return;
    imageInput.addEventListener("change", () => {
      if (imageInput.files.length < 1) return;          // 未選択
      if (imageInput.files[0].size > 5 * 1024 * 1024) { // 5MB超
        alert("5MB以下のファイルを選択してください。");
        imageInput.value = "";
      }
    });
  });
  </script>
</body>
</html>


