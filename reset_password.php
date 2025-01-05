<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');

// Kullanıcı doğrulama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_user'])) {
    $username = $_POST['reset_username'];
    $birthdate = $_POST['reset_birthdate'];
    $hint = $_POST['reset_hint'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND birthdate = ? AND hint = ?");
    $stmt->execute([$username, $birthdate, $hint]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['verified_user_id'] = $user['id']; // Kullanıcı doğrulandı, oturumda saklanıyor
        $verified = true;
    } else {
        $reset_error = "Kullanıcı adı, doğum tarihi veya ipucu hatalı!";
    }
}

// Şifre sıfırlama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password']) && isset($_SESSION['verified_user_id'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Şifrelerin eşleşip eşleşmediğini kontrol et
    if ($new_password !== $confirm_password) {
        $reset_error = "Yeni şifreler eşleşmiyor!";
    } else {
        if (strlen($new_password) >= 8 && 
            preg_match('/[A-Z]/', $new_password) &&
            preg_match('/[a-z]/', $new_password) &&
            preg_match('/[0-9]/', $new_password) &&
            preg_match('/[\W]/', $new_password)) {

            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $_SESSION['verified_user_id']]);

            unset($_SESSION['verified_user_id']); // Kullanıcı doğrulama tamamlandığında oturumdan sil
            $success = "Şifreniz başarıyla sıfırlandı!";
        } else {
            $reset_error = "Yeni şifre kriterlere uygun değil! Şifre en az 8 karakter uzunluğunda, büyük harf, küçük harf, sayı ve özel karakter içermelidir.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<section class="login-section">
    <main>
        <div class="left-side"></div>
        <div class="right-side">


            <!-- Kullanıcı Doğrulama Formu -->
            <?php if (!isset($_SESSION['verified_user_id'])): ?>
                <form class="form" method="POST">
                    <h1 style="color: red; ">Kullanıcı Doğrula</h1>

                    <label for="reset_username">Kullanıcı Adı</label>
                    <input type="text" name="reset_username" id="reset_username" placeholder="Kullanıcı Adı" required><br><br>

                    <label for="reset_birthdate">Doğum Tarihi</label>
                    <input type="date" name="reset_birthdate" id="reset_birthdate" required><br><br>

                    <label for="reset_hint">Şifre İpucu</label>
                    <input type="text" name="reset_hint" id="reset_hint" placeholder="Şifre İpucu" required><br><br>

                    <button type="submit" class="login-btn">Doğrula</button>
                   

                    <?php if (isset($reset_error)): ?>
                        <p style="color: red;"><?= $reset_error ?></p>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <!-- Şifre Sıfırlama Formu -->
                <form class="form" method="POST">
                    <h1>Şifre Sıfırla</h1>

                    <label for="new_password">Yeni Şifre</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Yeni Şifre" required><br><br>

                    <label for="confirm_password">Yeni Şifreyi Tekrar Girin</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Yeni Şifreyi Tekrar Girin" required><br><br>

                   
                    <button type="submit" class="login-btn">Şifreyi Güncelle</button>

                    <?php if (isset($reset_error)): ?>
                        <p style="color: red;"><?= $reset_error ?></p>
                    <?php elseif (isset($success)): ?>
                        <p style="color: green;"><?= $success ?></p>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </main>
</section>

<!-- Load particles.js library -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<!-- Include snow.js file -->
<script src="asset/snow.js"></script>
<?php include 'footer.php'; ?>

</body>
</html>
