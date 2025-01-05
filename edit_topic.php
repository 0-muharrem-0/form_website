<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');

$topic_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Konuyu kontrol et
$stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ? AND user_id = ?");
$stmt->execute([$topic_id, $user_id]);
$topic = $stmt->fetch();

if (!$topic) {
    die("Bu konuyu düzenleme yetkiniz yok!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Resim güncelleme
    $imagePath = $topic['image_path'];
    if (!empty($_FILES['images']['name'][0])) {
        $imageNames = [];
        foreach ($_FILES['images']['name'] as $index => $imageName) {
            $imageName = time() . '_' . $imageName;
            $imagePath = 'uploads/' . $imageName;
            move_uploaded_file($_FILES['images']['tmp_name'][$index], $imagePath);
            $imageNames[] = $imagePath;
        }
        $imagePath = implode(',', $imageNames);
    }

    // Konuyu güncelle
    $stmt = $pdo->prepare("UPDATE topics SET title = ?, content = ?, image_path = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $content, $imagePath, $topic_id, $user_id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Konu Düzenle</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Konu Düzenle</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required><br><br>
        <textarea name="content" rows="10" required><?= htmlspecialchars($topic['content']) ?></textarea><br><br>
        <label>Resim Güncelle:</label>
        <input type="file" name="images[]" multiple><br><br>
        <button type="submit">Güncelle</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
