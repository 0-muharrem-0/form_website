<?php
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$topics = $pdo->query("SELECT * FROM topics")->fetchAll();

foreach ($topics as $topic) {
    echo $topic['title'] . "<br>";
}
?>
