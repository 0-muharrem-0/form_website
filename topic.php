<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'];

if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("Geçersiz ID");
}

$topicStmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$topicStmt->execute([$id]);
$topic = $topicStmt->fetch();
?>

<html>
<head>
    <title>Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<!-- Article Section -->
<?php include 'article.php'; ?>

<!-- Comments Section -->
<?php include 'comments.php'; ?>

<?php include 'footer.php'; ?>

</body>
</html>
