<?php
require __DIR__.'/../../includes/app.php';
if (current_user()) redirect_to('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $time=time(); $fails=$_SESSION['login_fails'] ?? 0;
    $wait=$_SESSION['login_block_until'] ?? 0;
    if ($wait && $wait <= $time) { $fails=0; unset($_SESSION['login_fails'], $_SESSION['login_block_until']); }
    if ($wait > $time) {
        $error='Terlalu banyak percobaan. Coba kembali dalam beberapa menit.';
    } else {
        $username=trim((string)($_POST['username'] ?? ''));
        $password=(string)($_POST['password'] ?? '');
        $stmt=db()->prepare("SELECT id_pengelola,nama,username,password_hash,peran FROM pengelola WHERE username=? AND peran='admin' LIMIT 1");
        $stmt->execute([$username]); $user=$stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user']=['id'=>(int)$user['id_pengelola'],'nama'=>$user['nama'],'username'=>$user['username'],'peran'=>$user['peran']];
            unset($_SESSION['login_fails'],$_SESSION['login_block_until']);
            redirect_to('dashboard.php');
        }
        $fails++;
        $_SESSION['login_fails']=$fails;
        if ($fails>=5) $_SESSION['login_block_until']=$time+900;
        $error='Username atau password tidak sesuai.';
    }
}
$pageTitle='Login Admin';$rootPrefix='../../';$isAdmin=true;$isLogin=true;
require __DIR__.'/../../includes/header.php';
?>
<!-- ===========================================================
  LOGIN ADMIN — Layout mengikuti high-fidelity Figma pengguna.
  Foto latar: assets/images/mitra/login.jpg (edit FOTO, bukan PHP).
  Form username/password asli dan logika autentikasi PHP tidak diubah.
============================================================ -->
<div class="login-wrap login-figma-v8">
  <!-- Panel kiri: headline, nilai layanan, dan branding mitra. -->
  <section class="login-showcase" aria-label="Tentang dWarna Laundry">
    <a class="login-logo" href="../../index.php">
      <img src="../../assets/images/logo/logo-mitra.jpg" alt="Logo dWarna Laundry">
      <span><b>dWarna</b><small>Laundry</small></span>
    </a>
    <div class="login-copy">
      <h1>Bersama,<br><em>Melayani Lebih Baik</em></h1>
      <p>Kelola setiap pesanan dengan rapi, cepat, dan penuh tanggung jawab. Jadikan setiap layanan laundry pengalaman terbaik untuk pelanggan.</p>
      <div class="login-benefits" aria-label="Keunggulan sistem pengelolaan">
        <div class="login-benefit">
          <span class="login-benefit-icon" aria-hidden="true">♧</span>
          <span><strong>Kerja Lebih Terarah</strong><small>Kelola pesanan dengan sistem yang rapi dan mudah.</small></span>
        </div>
        <div class="login-benefit">
          <span class="login-benefit-icon" aria-hidden="true">ϟ</span>
          <span><strong>Proses Lebih Cepat</strong><small>Dukung layanan yang efisien dan tepat waktu.</small></span>
        </div>
        <div class="login-benefit">
          <span class="login-benefit-icon" aria-hidden="true">◇</span>
          <span><strong>Pelayanan Lebih Baik</strong><small>Bersama menjaga kepuasan dan kepercayaan pelanggan.</small></span>
        </div>
      </div>
    </div>
  </section>

  <!-- Panel kanan: formulir login nyata, tetap POST + CSRF. -->
  <section class="login-right" aria-label="Login Admin dWarna Laundry">
    <div class="login-box">
      <div class="login-box-head">
        <img src="../../assets/images/logo/logo-mitra.jpg" alt="Logo dWarna Laundry">
        <span><b>dWarna</b><small>Laundry</small></span>
      </div>
      <h2>Login</h2>
      <p>Masuk untuk mengelola pesanan laundry</p>
      <?=show_flash()?>
      <?php if ($error): ?>
        <div role="alert" class="app-alert alert-error"><?=h($error)?></div>
      <?php endif; ?>
      <form method="post" autocomplete="on">
        <?=csrf_input()?>
        <div class="form-field login-form-field">
          <label class="sr-only" for="username">Username Admin</label>
          <div class="login-field-wrap">
            <span class="login-field-icon" aria-hidden="true">✉</span>
            <input id="username" name="username" type="text" required autocomplete="username" placeholder="Username Admin" maxlength="40" value="<?=h($_POST['username'] ?? '')?>">
          </div>
        </div>
        <div class="form-field login-form-field">
          <label class="sr-only" for="password">Password</label>
          <div class="login-field-wrap password-row">
            <span class="login-field-icon" aria-hidden="true">♙</span>
            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Password">
            <button type="button" data-toggle-password="password" aria-label="Lihat atau sembunyikan password">Lihat</button>
          </div>
        </div>
        <button class="btn login-submit" type="submit">Masuk</button>
      </form>
      <p class="login-access-note">♧ &nbsp; Khusus untuk karyawan dan pemilik</p>
      <a class="back-home" href="../../index.php">← Kembali ke halaman pelanggan</a>
    </div>
  </section>
</div>
<?php require __DIR__.'/../../includes/footer.php'; ?>
