<?php
// db_test.php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Bağlantı başarılı!";
} catch (PDOException $e) {
    die("Bağlantı başarısız: " . $e->getMessage());
}
?>
