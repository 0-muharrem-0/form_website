<?php
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'];

// ID'nin geçerli olduğundan emin ol
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("Geçersiz ID");
}

// Görüntüleme sayısını artır
$updateViewsStmt = $pdo->prepare("UPDATE topics SET views = views + 1 WHERE id = ?");
$updateViewsStmt->execute([$id]);

$topicStmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$topicStmt->execute([$id]);
$topic = $topicStmt->fetch();

$imagePaths = json_decode($topic['image_path'], true);
?>

<section class="article-content">
    <div class="container">
        <h1 class="main-title"><?= htmlspecialchars($topic['title']) ?></h1>
        <p class="p-content"><?= htmlspecialchars($topic['content']) ?></p>

        <?php if ($imagePaths): ?>
            <div class="image-gallery">
                <?php foreach ($imagePaths as $imagePath): ?>
                    <a href="<?= htmlspecialchars($imagePath) ?>" target="_blank">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Resim" class="topic-image">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>