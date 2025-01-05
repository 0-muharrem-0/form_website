<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Giriş yapmayan kullanıcıları login sayfasına yönlendir
    header("Location: login.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topicId = $_POST['topic_id'];
    $parentId = $_POST['parent_id'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id'];

    // `parent_id` değerinin geçerli olup olmadığını kontrol et
    if ($parentId != 0) {
        $parentStmt = $pdo->prepare("SELECT id FROM comments WHERE id = ?");
        $parentStmt->execute([$parentId]);
        $parentComment = $parentStmt->fetch();
        if (!$parentComment) {
            die("Geçersiz parent_id");
        }
    } else {
        $parentId = NULL; // `parent_id` yoksa NULL olarak ayarla
    }

    $stmt = $pdo->prepare("INSERT INTO comments (topic_id, user_id, parent_id, content) VALUES (?, ?, ?, ?)");
    $stmt->execute([$topicId, $userId, $parentId, $content]);

    header("Location: topic.php?id=" . $topicId);
    exit;
}
?>
