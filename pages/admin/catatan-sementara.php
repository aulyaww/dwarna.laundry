<?php
require __DIR__.'/../../includes/app.php';require_admin();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    validate_csrf();
    $name=trim((string)($_POST['nama_pelanggan']??''));$wa=trim((string)($_POST['no_whatsapp']??''));
    $detail=trim((string)($_POST['rincian_layanan']??''));$note=trim((string)($_POST['catatan']??''));
    if(strlen($name)<2 || strlen($name)>100 || !preg_match('/^\+?[0-9]{9,16}$/',$wa) || strlen($detail)<3 || strlen($detail)>2000 || strlen($note)>2000) $error='Periksa nama, nomor WhatsApp, dan rincian layanan.';
    else {
        $code='DWT-'.strtoupper(bin2hex(random_bytes(4)));
        $stmt=db()->prepare('INSERT INTO catatan_sementara (kode_sementara,nama_pelanggan,no_whatsapp,rincian_layanan,catatan,id_pengelola) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$code,$name,$wa,$detail,$note?:null,current_user()['id']]);
        flash('success','Catatan '.$code.' berhasil dimasukkan. Data ini belum menjadi pesanan resmi.');redirect_to('catatan-sementara.php');
    }
}
$rows=db()->query('SELECT kode_sementara,nama_pelanggan,no_whatsapp,rincian_layanan,status_sinkronisasi,dibuat_pada FROM catatan_sementara ORDER BY id_catatan DESC LIMIT 100')->fetchAll();
admin_page_head('Catatan Sementara','Masukkan kembali catatan manual setelah sistem pulih.');
?>
<div class="app-alert alert-info">Jika komputer atau database sedang mati, catat pesanan di kertas/offline dulu. Form ini hanya bisa digunakan setelah server kembali aktif.</div>
<section class="admin-card form-width"><h2>Input Catatan Manual</h2><?php if($error):?><div role="alert" class="app-alert alert-error"><?=h($error)?></div><?php endif;?>
<form method="post"><?=csrf_input()?><div class="two-col-form"><div class="form-field"><label>Nama Pelanggan</label><input required maxlength="100" name="nama_pelanggan" value="<?=h($_POST['nama_pelanggan']??'')?>"></div><div class="form-field"><label>WhatsApp</label><input required inputmode="tel" name="no_whatsapp" value="<?=h($_POST['no_whatsapp']??'')?>"></div></div><div class="form-field"><label>Rincian Layanan</label><textarea required name="rincian_layanan" maxlength="2000" rows="3"><?=h($_POST['rincian_layanan']??'')?></textarea></div><div class="form-field"><label>Catatan (opsional)</label><textarea name="catatan" maxlength="2000" rows="2"><?=h($_POST['catatan']??'')?></textarea></div><button type="submit" class="btn">Simpan Catatan</button></form></section>
<section class="admin-card" style="margin-top:20px"><div class="admin-card-head"><h2>Daftar Catatan</h2></div><div class="table-wrap"><table class="data-table"><thead><tr><th>Kode</th><th>Pelanggan</th><th>WhatsApp</th><th>Rincian</th><th>Status</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td class="code-cell"><?=h($r['kode_sementara'])?></td><td><?=h($r['nama_pelanggan'])?></td><td><?=h($r['no_whatsapp'])?></td><td><?=h($r['rincian_layanan'])?></td><td><?=h($r['status_sinkronisasi'])?></td></tr><?php endforeach;?><?php if(!$rows):?><tr><td colspan="5">Belum ada catatan.</td></tr><?php endif;?></tbody></table></div><p class="inline-help">Penautan catatan menjadi pesanan resmi dilakukan secara terpisah di menu Tambah Pesanan; sinkronisasi otomatis belum diterapkan.</p></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
