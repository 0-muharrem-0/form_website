<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Giriş yapmayan kullanıcıları login sayfasına yönlendir
    header("Location: login.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category']; // Seçilen kategori
    $userId = $_SESSION['user_id']; // Giriş yapan kullanıcının ID'si

    // Resim yükleme
    $imagePaths = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $imageName = time() . '_' . $imageName;
            $imagePath = 'uploads/' . $imageName;
            move_uploaded_file($_FILES['images']['tmp_name'][$key], $imagePath);
            $imagePaths[] = $imagePath;
        }
    }
    $imagePaths = json_encode($imagePaths);

    // Veritabanına ekle
    $stmt = $pdo->prepare("INSERT INTO topics (title, content, category, image_path, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $category, $imagePaths, $userId]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Konu Ekle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <section class="add-topic-section">
        <h1>Yeni Konu Ekle</h1>
        <form class="add-topic-form" method="POST" enctype="multipart/form-data">
            <label for="title">Başlık:</label>
            <input type="text" id="title" name="title" placeholder="Başlık" required>

            <label for="content">İçerik:</label>
            <textarea id="content" name="content" placeholder="İçeriği buraya yazın..." required></textarea>

            <label for="category">Bölüm Seçin:</label>
            <select id="category" name="category" required style="height: 40px; width: 100%; font-size: 16px;">
                <option value="Bilgisayar Programcılığı">Bilgisayar Programcılığı Bölümü</option>
                <option value="Elektrik Bölümü">Elektrik Bölümü</option>
                <option value="Makine">Makine Bölümü</option>
                <option value="Tekstil">Tekstil Bölümü</option>
                <option value="İnşaat Teknolojisi">İnşaat Teknolojisi</option>
            </select>


            <label for="images">Resimler Ekle:</label>
            <input type="file" id="images" name="images[]" multiple>

            <button type="submit">Ekle</button>
        </form>
    </section>
</body>
</html>
