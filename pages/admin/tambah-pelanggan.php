<?php
require __DIR__.'/../../includes/app.php'; require_admin();
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    validate_csrf();
    $name=trim((string)($_POST['nama']??''));
    $wa=trim((string)($_POST['no_whatsapp']??''));
    $addr=trim((string)($_POST['alamat']??''));
    if (strlen($name)<2 || strlen($name)>100) $error='Nama pelanggan wajib diisi (2–100 karakter).';
    elseif (!preg_match('/^\+?[0-9]{9,16}$/', $wa)) $error='Nomor WhatsApp harus 9–16 digit, boleh diawali +.';
    elseif (strlen($addr)>1000) $error='Alamat terlalu panjang.';
    else {
        $q=db()->prepare('INSERT INTO pelanggan (nama,no_whatsapp,alamat) VALUES (?,?,?)');
        $q->execute([$name,$wa,$addr ?: null]);
        flash('success','Pelanggan berhasil ditambahkan.'); redirect_to('pelanggan.php');
    }
}
admin_page_head('Tambah Pelanggan','Masukkan data pelanggan yang akan digunakan untuk pesanan.');
?>
<section class="admin-card form-width"><h2>Informasi Pelanggan</h2><?php if($error):?><div class="app-alert alert-error"><?=h($error)?></div><?php endif; ?>
<form method="post"><?=csrf_input()?><div class="two-col-form"><div class="form-field"><label for="nama">Nama Lengkap</label><input id="nama" name="nama" required maxlength="100" value="<?=h($_POST['nama']??'')?>" placeholder="Nama pelanggan"></div><div class="form-field"><label for="no_whatsapp">Nomor WhatsApp</label><input id="no_whatsapp" name="no_whatsapp" required inputmode="tel" maxlength="20" value="<?=h($_POST['no_whatsapp']??'')?>" placeholder="08xxxxxxxxxx"></div></div><div class="form-field"><label for="alamat">Alamat (opsional)</label><textarea id="alamat" name="alamat" rows="3" maxlength="1000" placeholder="Alamat pelanggan"><?=h($_POST['alamat']??'')?></textarea></div><div class="form-actions"><a class="btn btn-secondary" href="pelanggan.php">Batal</a><button class="btn" type="submit">Simpan Pelanggan</button></div></form></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
