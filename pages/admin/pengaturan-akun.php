<?php
/** Ubah username dan password akun tunggal Admin tanpa menghapus relasi pesanan. */
require __DIR__.'/../../includes/app.php';
require_admin();

$errors=[];
$userId=(int)current_user()['id'];
$stmt=db()->prepare('SELECT id_pengelola,username,password_hash FROM pengelola WHERE id_pengelola=? LIMIT 1');
$stmt->execute([$userId]);
$account=$stmt->fetch();
if (!$account) {
    unset($_SESSION['user']);
    redirect_to('login.php');
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    validate_csrf();
    $username=trim((string)($_POST['username'] ?? ''));
    $currentPassword=(string)($_POST['password_lama'] ?? '');
    $newPassword=(string)($_POST['password_baru'] ?? '');
    $confirm=(string)($_POST['konfirmasi_password'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9._-]{3,40}$/',$username)) {
        $errors[]='Username harus 3–40 karakter dan hanya menggunakan huruf, angka, titik, garis bawah, atau tanda hubung.';
    }
    if (!password_verify($currentPassword,(string)$account['password_hash'])) {
        $errors[]='Password lama tidak sesuai.';
    }
    if (strlen($newPassword)<12) $errors[]='Password baru minimal 12 karakter.';
    if ($newPassword!==$confirm) $errors[]='Konfirmasi password baru belum sama.';
    if (!$errors) {
        try {
            $save=db()->prepare("UPDATE pengelola SET nama='Admin', peran='admin', username=?, password_hash=? WHERE id_pengelola=?");
            $save->execute([$username,password_hash($newPassword,PASSWORD_DEFAULT),$userId]);
            // Kredensial diganti: wajib login ulang agar sesi lama tidak dipakai terus.
            unset($_SESSION['user']);
            session_regenerate_id(true);
            flash('success','Akun Admin diperbarui. Silakan masuk memakai username dan password baru.');
            redirect_to('login.php');
        } catch (PDOException $e) {
            $errors[]='Tidak dapat menyimpan akun. Pastikan username belum digunakan.';
        }
    }
}

admin_page_head('Akun Admin','Satu akun bersama untuk mengelola pesanan dWarna Laundry.');
?>
<section class="admin-card form-width">
  <div class="admin-card-head"><div><h2>Ganti Username & Password</h2><p>Tidak perlu mengisi nama pemilik atau membuat akun baru.</p></div></div>
  <?php foreach($errors as $error): ?><div role="alert" class="app-alert alert-error"><?=h($error)?></div><?php endforeach; ?>
  <form method="post" autocomplete="off">
    <?=csrf_input()?>
    <div class="form-field">
      <label for="username">Username Admin</label>
      <input id="username" name="username" type="text" required minlength="3" maxlength="40" pattern="[A-Za-z0-9._-]+" autocomplete="username" value="<?=h($_POST['username'] ?? $account['username'])?>">
    </div>
    <div class="form-field">
      <label for="password_lama">Password Sekarang</label>
      <input id="password_lama" name="password_lama" type="password" required autocomplete="current-password" placeholder="Masukkan password akun yang digunakan saat ini">
    </div>
    <div class="form-field">
      <label for="password_baru">Password Baru (minimal 12 karakter)</label>
      <input id="password_baru" name="password_baru" type="password" required minlength="12" autocomplete="new-password">
    </div>
    <div class="form-field">
      <label for="konfirmasi_password">Ulangi Password Baru</label>
      <input id="konfirmasi_password" name="konfirmasi_password" type="password" required minlength="12" autocomplete="new-password">
    </div>
    <p class="inline-help">Akun lama tidak dihapus: username, password, dan labelnya diperbarui menjadi Admin agar riwayat pesanan tetap utuh. Setelah disimpan kamu akan diminta login ulang.</p>
    <div class="form-actions"><a href="dashboard.php" class="btn btn-secondary">Batal</a><button class="btn" type="submit">Simpan Akun Admin</button></div>
  </form>
</section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
