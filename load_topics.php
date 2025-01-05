<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

$limit = 7;
$table = 'topics';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$panel = isset($_POST['panel']) ? $_POST['panel'] : '';
echo "Panel: $panel<br>"; // Hata ayıklama için panel değişkenini yazdırın

function fetchTopics($pdo, $category, $limit, $offset) {
    try {
        $stmt = $pdo->prepare("
            SELECT topics.*, users.username 
            FROM topics 
            LEFT JOIN users ON topics.user_id = users.id 
            WHERE topics.category = :category 
            ORDER BY topics.created_at DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "SQL Sorgu hatası: " . $e->getMessage();
        return [];
    }
}

function fetchTotalPages($pdo, $category, $limit) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM topics WHERE category = :category");
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $totalTopics = $stmt->fetchColumn();
        return ceil($totalTopics / $limit);
    } catch (PDOException $e) {
        echo "SQL Sorgu hatası: " . $e->getMessage();
        return 1;
    }
}

if ($panel === 'panel1') {
    $topics = fetchTopics($pdo, 'Bilgisayar Programcılığı', $limit, $offset);
    $totalPages = fetchTotalPages($pdo, 'Bilgisayar Programcılığı', $limit);
    $panelId = 'panel1';
    include 'topics1.php';
} else if ($panel === 'panel2') {
    $topics = fetchTopics($pdo, 'Elektrik Bölümü', $limit, $offset);
    $totalPages = fetchTotalPages($pdo, 'Elektrik Bölümü', $limit);
    $panelId = 'panel2';
    include 'topics2.php';
} else if ($panel === 'panel3') {
    $topics = fetchTopics($pdo, 'Makine', $limit, $offset);
    $totalPages = fetchTotalPages($pdo, 'Makine', $limit);
    $panelId = 'panel3';
    include 'topics3.php';
} else if ($panel === 'panel4') {
    $topics = fetchTopics($pdo, 'Tekstil', $limit, $offset);
    $totalPages = fetchTotalPages($pdo, 'Tekstil', $limit);
    $panelId = 'panel4';
    include 'topics4.php';
} else if ($panel === 'panel5') {
    $topics = fetchTopics($pdo, 'İnşaat Teknolojisi', $limit, $offset);
    $totalPages = fetchTotalPages($pdo, 'İnşaat Teknolojisi', $limit);
    $panelId = 'panel5';
    include 'topics5.php';
}
?>
