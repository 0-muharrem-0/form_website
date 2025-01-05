<?php
session_start();

// Database connection using PDO with error handling
try {
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre!";
        }
    } else {
        $error = "Geçersiz CSRF token!";
    }
}

// Generate a new CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
      <form action="" method="POST">
 

      <div class="or">GİRİŞ</div>

      <label for="email">Kullanıcı Adı</label>
      <input type="text" placeholder="Kullanıcı Adı" name="username" required />

      <label for="password">Şifre</label>
      <input type="password" placeholder="Şifre" name="password" required />

      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      
      <button type="submit" class="login-btn">Giriş Yap</button>
      <div class="links">
        <a href="reset_password.php">Şifreni Mi Unuttun?</a>
        <a href="register.php">Hesabın Yok Mu?</a>
      </div>

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
<?php include 'footer.php'; ?>

</body>
</html>


