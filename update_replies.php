<?php
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$topic_id = $_GET['topic_id'];

if (!filter_var($topic_id, FILTER_VALIDATE_INT)) {
    die("Geçersiz Topic ID");
}

// Yorum sayısını güncelle
$updateRepliesStmt = $pdo->prepare("UPDATE topics SET replies = replies + 1 WHERE id = ?");
$updateRepliesStmt->execute([$topic_id]);

echo "Yorum sayısı güncellendi";
?>
