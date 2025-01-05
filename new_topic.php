<?php
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');

$title = "İlk Konu";
$content = "Bu forumun ilk konusu.";
$stmt = $pdo->prepare("INSERT INTO topics (title, content) VALUES (?, ?)");
$stmt->execute([$title, $content]);

echo "Konu başarıyla eklendi!";
?>
