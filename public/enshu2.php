<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

if (isset($_POST['body'])) {
    // 投稿された本文を保存
    $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body) VALUES (:body)");
    $insert_sth->execute([':body' => $_POST['body']]);

    header("Location: ./enshu2.php");
    exit;
}

// 検索クエリ取得（GETパラメータ）
$query = isset($_GET['query']) ? trim($_GET['query']) : "";

// 検索条件によってSQLを変更
if ($query !== "") {
    $select_sth = $dbh->prepare("SELECT * FROM bbs_entries WHERE body LIKE :query ORDER BY created_at DESC");
    $select_sth->execute([':query' => "%{$query}%"]);
} else {
    $select_sth = $dbh->prepare("SELECT * FROM bbs_entries ORDER BY created_at DESC");
    $select_sth->execute();
}
?>

<!-- 投稿フォーム -->
<form method="POST" action="./enshu2.php">
    <textarea name="body" placeholder="投稿内容を入力"></textarea>
    <button type="submit">送信</button>
</form>

<br>

<!-- 検索フォーム -->
<form method="GET" action="./enshu2.php">
    <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="検索ワードを入力">
    <button type="submit">検索</button>
</form>

<!-- 検索状態表示 -->
<?php if ($query !== ""): ?>
    <p>現在「<?= htmlspecialchars($query) ?>」で検索中。 <a href="enshu2.php">検索解除</a></p>
<?php endif; ?>

<hr>

<!-- 投稿表示 -->
<?php foreach ($select_sth as $entry): ?>
    <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
        <dt>ID</dt>
        <dd><?= $entry['id'] ?></dd>
        <dt>日時</dt>
        <dd><?= $entry['created_at'] ?></dd>
        <dt>内容</dt>
        <dd><?= nl2br(htmlspecialchars($entry['body'])) ?></dd>
    </dl>
<?php endforeach; ?>








