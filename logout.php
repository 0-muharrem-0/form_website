<?php
session_start();

// Oturumu sonlandır
session_unset(); // Tüm oturum değişkenlerini sil
session_destroy(); // Oturumu yok et

// Anasayfaya yönlendir
header("Location: index.php");
exit;
?>
