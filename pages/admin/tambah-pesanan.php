<?php
require __DIR__.'/../../includes/app.php'; require_admin();
$customers=db()->query('SELECT id_pelanggan,nama,no_whatsapp FROM pelanggan ORDER BY nama')->fetchAll();
$services=db()->query('SELECT id_layanan,nama_layanan,harga_satuan,satuan FROM layanan ORDER BY id_layanan')->fetchAll();
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    validate_csrf();
    $customer=(int)($_POST['id_pelanggan']??0); $service=(int)($_POST['id_layanan']??0);
    $quantity=(float)($_POST['jumlah']??0);
    $estimate=trim((string)($_POST['estimasi_selesai']??''));
    $note=trim((string)($_POST['catatan']??''));
    $q=db()->prepare('SELECT harga_satuan FROM layanan WHERE id_layanan=?');$q->execute([$service]);$price=$q->fetchColumn();
    $q=db()->prepare('SELECT COUNT(*) FROM pelanggan WHERE id_pelanggan=?');$q->execute([$customer]);$exists=(int)$q->fetchColumn();
    if (!$exists || $price===false) $error='Pilih pelanggan dan layanan yang valid.';
    elseif (!is_finite($quantity) || $quantity<=0 || $quantity>100000) $error='Jumlah/berat harus lebih dari 0.';
    elseif ($estimate && (!strtotime($estimate) || strlen($estimate)>16)) $error='Format estimasi selesai tidak benar.';
    elseif(strlen($note)>2000) $error='Catatan terlalu panjang.';
    else {
        try {
            db()->beginTransaction();
            $code='DW'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            $sql='INSERT INTO pesanan (kode_pesanan,id_pelanggan,id_layanan,id_pengelola,jumlah,total_harga,estimasi_selesai,status,catatan) VALUES (?,?,?,?,?,?,?,\'Diterima\',?)';
            $q=db()->prepare($sql);
            $q->execute([$code,$customer,$service,current_user()['id'],$quantity,round($quantity*(float)$price,2),$estimate ? date('Y-m-d H:i:s',strtotime($estimate)) : null,$note?:null]);
            $id=(int)db()->lastInsertId();
            $q=db()->prepare("INSERT INTO riwayat_status (id_pesanan,id_pengelola,status_baru) VALUES (?,?,'Diterima')");
            $q->execute([$id,current_user()['id']]);
            db()->commit(); flash('success','Pesanan '.$code.' berhasil ditambahkan.');redirect_to('detail-pesanan.php?id='.$id);
        } catch(Throwable $ex) { if(db()->inTransaction())db()->rollBack();$error='Gagal menyimpan pesanan. Coba lagi.'; }
    }
}
admin_page_head('Tambah Pesanan','Pilih pelanggan dan layanan, lalu simpan ke database.');
?>
<section class="admin-card form-width"><h2>Informasi Pesanan</h2><?php if($error):?><div class="app-alert alert-error"><?=h($error)?></div><?php endif;?>
<?php if(!$customers || !$services):?><div class="app-alert alert-error">Tambahkan pelanggan dan data layanan terlebih dahulu.</div><?php endif;?>
<form method="post"><?=csrf_input()?><div class="two-col-form"><div class="form-field"><label for="customer">Pelanggan</label><select name="id_pelanggan" id="customer" required><option value="">Pilih pelanggan</option><?php foreach($customers as $c):?><option value="<?=(int)$c['id_pelanggan']?>" <?=((int)($_POST['id_pelanggan']??0)===(int)$c['id_pelanggan'])?'selected':''?>><?=h($c['nama'].' • '.$c['no_whatsapp'])?></option><?php endforeach;?></select></div><div class="form-field"><label for="service">Layanan</label><select name="id_layanan" id="service" required><option value="">Pilih layanan</option><?php foreach($services as $s):?><option value="<?=(int)$s['id_layanan']?>" <?=((int)($_POST['id_layanan']??0)===(int)$s['id_layanan'])?'selected':''?>><?=h($s['nama_layanan'].' — '.rupiah($s['harga_satuan']).'/'.$s['satuan'])?></option><?php endforeach;?></select></div><div class="form-field"><label for="qty">Berat/Jumlah</label><input type="number" id="qty" name="jumlah" required min="0.01" max="100000" step="0.01" value="<?=h($_POST['jumlah']??'1')?>"></div><div class="form-field"><label for="estimate">Perkiraan Selesai (opsional)</label><input type="datetime-local" id="estimate" name="estimasi_selesai" value="<?=h($_POST['estimasi_selesai']??'')?>"></div></div><div class="form-field"><label for="note">Catatan (opsional)</label><textarea name="catatan" id="note" rows="4" maxlength="2000" placeholder="Contoh: pisahkan pakaian putih"><?=h($_POST['catatan']??'')?></textarea></div><p class="inline-help">Estimasi total layanan dihitung otomatis di server berdasarkan harga layanan dan jumlah. Tidak ada fitur pembayaran.</p><div class="form-actions"><a class="btn btn-secondary" href="pesanan.php">Batal</a><button class="btn" type="submit" <?=(!$customers||!$services)?'disabled':''?>>Simpan Pesanan</button></div></form></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
