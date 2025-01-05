<?php
session_start();

// Veritabanı bağlantısı PDO kullanarak, hata ayıklama ile
try {
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $confirm_password = htmlspecialchars($_POST['confirm_password']);
        $birthdate = htmlspecialchars($_POST['birthdate']);
        $hint = htmlspecialchars($_POST['hint']);

        // Şifre kriterlerini kontrol et
        if (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[\W]/', $password)) {
            $error = "Şifre en az 8 karakter uzunluğunda olmalı ve büyük harf, küçük harf, sayı, özel karakter içermelidir!";
        } elseif ($password !== $confirm_password) {
            $error = "Şifreler uyuşmuyor!";
        } elseif (empty($birthdate) || empty($hint)) {
            $error = "Doğum tarihi ve ipucu alanları boş bırakılamaz!";
        } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthdate)) {
            $error = "Geçersiz doğum tarihi formatı!";
        } else {
            // Kullanıcı adı kontrolü
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $error = "Bu kullanıcı adı zaten alınmış!";
            } else {
                // Şifreyi hash'le
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Yeni kullanıcıyı kaydet
                $stmt = $pdo->prepare("INSERT INTO users (username, password, birthdate, hint) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $hashedPassword, $birthdate, $hint]);

                // Oturum başlat ve yönlendir
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            }
        }
    } else {
        $error = "Geçersiz CSRF token!";
    }
}

// Yeni bir CSRF token oluştur
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.form').addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]').value;
                const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
                const birthdate = document.querySelector('input[name="birthdate"]').value;
                const hint = document.querySelector('input[name="hint"]').value;
                const errorElement = document.querySelector('#form-error');
                
                const passwordCriteria = [
                    /.{8,}/,         // En az 8 karakter
                    /[A-Z]/,         // Büyük harf
                    /[a-z]/,         // Küçük harf
                    /[0-9]/,         // Rakam
                    /[\W]/           // Özel karakter
                ];
                
                const isValidPassword = passwordCriteria.every(regex => regex.test(password));
                let errorMessage = "";

                if (!isValidPassword) {
                    errorMessage = "Şifre en az 8 karakter uzunluğunda, büyük harf, küçük harf, sayı ve özel karakter içermelidir!";
                } else if (password !== confirmPassword) {
                    errorMessage = "Şifreler uyuşmuyor!";
                } else if (!birthdate) {
                    errorMessage = "Doğum tarihi boş bırakılamaz!";
                } else if (!hint) {
                    errorMessage = "Şifre ipucu boş bırakılamaz!";
                }

                if (errorMessage) {
                    e.preventDefault();
                    errorElement.textContent = errorMessage;
                    errorElement.style.color = 'red';
                } else {
                    errorElement.textContent = "";
                }
            });
        });
    </script>
</head>
<body>
<?php include 'navbar.php'; ?>

<section class="login-section">
  <main>
     <div class="left-side"></div>
    
    <div class="right-side">
      <form action="" method="POST" class="form">
      <label for="username">Kullanıcı Adı</label>
      <input type="text" placeholder="Kullanıcı Adı" name="username" required />

      <label for="password">Şifre</label>
      <input type="password" placeholder="Şifre" name="password" required />

      <label for="confirm_password">Şifreyi Doğrula</label>
      <input type="password" placeholder="Şifreyi Doğrula" name="confirm_password" required />

      <label for="birthdate">Doğum Tarihi</label>
      <input type="date" name="birthdate" required />

      <label for="hint">Şifre İpucu</label>
      <input type="text" placeholder="Şifre İpucu" name="hint" required />

      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      
      <button type="submit" class="login-btn">Kayıt Ol</button>
      
      <div id="form-error"></div>

      <?php if (isset($error)): ?>
          <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>
    </form>
    </div>
  </main>
</section>

<!-- Load particles.js library -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<!-- Include snow.js file -->
<script src="asset/snow.js"></script>

</body>
</html>
