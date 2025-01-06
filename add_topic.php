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
    $category = $_POST['category'];
    $userId = $_SESSION['user_id']; 

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <section class="add-topic-section">
        <h1><i class="fas fa-pencil-alt"></i> Yeni Konu Ekle</h1>
        <form class="add-topic-form" method="POST" enctype="multipart/form-data">
            <label for="title"><i class="fas fa-heading"></i> Başlık:</label>
            <input type="text" id="title" name="title" placeholder="Başlık" required>

            <label for="content"><i class="fas fa-align-left"></i> İçerik:</label>
            <textarea id="content" name="content" placeholder="İçeriği buraya yazın..." required></textarea>

            <label for="category"><i class="fas fa-list"></i> Bölüm Seçin:</label>
            <select id="category" name="category" required style="height: 40px; width: 100%; font-size: 16px;">
                <option value="Bilgisayar Programcılığı"><i class="fas fa-laptop-code"></i> Bilgisayar Programcılığı Bölümü</option>
                <option value="Elektrik Bölümü"><i class="fas fa-bolt"></i> Elektrik Bölümü</option>
                <option value="Makine"><i class="fas fa-cogs"></i> Makine Bölümü</option>
                <option value="Tekstil"><i class="fas fa-tshirt"></i> Tekstil Bölümü</option>
                <option value="İnşaat Teknolojisi"><i class="fas fa-building"></i> İnşaat Teknolojisi</option>
            </select>

            <label for="images"><i class="fas fa-image"></i> Resimler Ekle:</label>
            <input type="file" id="images" name="images[]" multiple>

            <button type="submit"><i class="fas fa-plus"></i> Ekle</button>
        </form>
    </section>
</body>
</html>
