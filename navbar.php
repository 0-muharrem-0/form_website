<header>
  <p class="Logo">İNÖNÜ ÜNİVERSİTESİ</p>
  <input type="checkbox" id="nav-toggle" class="btn" />
  <label for="nav-toggle" class="toggle-label">
    <i class="fas fa-bars"></i> <!-- Font Awesome icon for toggle -->
  </label>
  <div class="nav">
    <ol>
      <li><a href="index.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
      <li><a href="add_topic.php"><i class="fas fa-book"></i> Makaleler</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
      <?php else: ?>
        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a></li>
        <li><a href="register.php"><i class="fas fa-user-plus"></i> Kayıt Ol</a></li>
      <?php endif; ?>
    </ol>
  </div>
</header>
