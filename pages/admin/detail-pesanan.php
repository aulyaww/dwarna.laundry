<?php
require __DIR__.'/../../includes/app.php'; require_admin();
$id=(int)($_GET['id']??0);$order=$id>0?query_order($id):null;
if (!$order) {http_response_code(404);exit('Pesanan tidak ditemukan. <a href="pesanan.php">Kembali ke daftar</a>');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    validate_csrf();
    $new=(string)($_POST['status']??'');
    if (!in_array($new,['Diterima','Diproses','Dikemas','Selesai'],true))$error='Status tidak valid.';
    elseif($new===$order['status'])$error='Status pesanan belum berubah.';
    else {
        try {
            db()->beginTransaction();
            $q=db()->prepare('UPDATE pesanan SET status=? WHERE id_pesanan=?');$q->execute([$new,$id]);
            $q=db()->prepare('INSERT INTO riwayat_status(id_pesanan,id_pengelola,status_baru)VALUES (?,?,?)');$q->execute([$id,current_user()['id'],$new]);
            if($new==='Selesai') {
                $message='Halo '.$order['pelanggan_nama'].', laundry dengan kode '.$order['kode_pesanan'].' sudah selesai dan dapat diambil di dWarna Laundry. Terima kasih.';
                $q=db()->prepare("INSERT INTO notifikasi(id_pesanan,id_pengelola,kanal,pesan,status_pengiriman) VALUES (?,?,'WhatsApp',?,'Disiapkan')");
                $q->execute([$id,current_user()['id'],$message]);
            }
            db()->commit();flash('success','Status pesanan berhasil diperbarui.');redirect_to('detail-pesanan.php?id='.$id);
        } catch(Throwable $e){if(db()->inTransaction())db()->rollBack();$error='Gagal memperbarui status.';}
    }
}
$history=db()->prepare('SELECT r.status_baru,r.berubah_pada,a.nama AS pengelola FROM riwayat_status r LEFT JOIN pengelola a ON r.id_pengelola=a.id_pengelola WHERE r.id_pesanan=? ORDER BY r.berubah_pada DESC,r.id_riwayat DESC');$history->execute([$id]);
$history=$history->fetchAll();
admin_page_head('Detail Pesanan','Informasi dan perubahan status pesanan.');
?>
<div class="page-quick-action"><a class="section-link" href="pesanan.php">← Kembali ke Pesanan</a></div>
<?php if($error):?><div role="alert" class="app-alert alert-error"><?=h($error)?></div><?php endif;?>
<div class="detail-grid"><section class="admin-card"><div class="admin-card-head"><h2><?=h($order['kode_pesanan'])?></h2><?=status_badge($order['status'])?></div><h3>Data Pelanggan</h3><div class="kv-grid app-kv"><span>Nama<b><?=h($order['pelanggan_nama'])?></b></span><span>WhatsApp<b><?=h($order['no_whatsapp'])?></b></span></div><div class="divider-line"></div><h3>Detail Laundry</h3><div class="kv-grid app-kv"><span>Layanan<b><?=h($order['nama_layanan'])?></b></span><span>Jumlah<b><?=h((string)$order['jumlah']).' '.h($order['satuan'])?></b></span><span>Estimasi harga layanan<b><?=rupiah($order['total_harga'])?></b></span><span>Tanggal Masuk<b><?=h(waktu($order['tanggal_masuk']))?></b></span><span>Estimasi Selesai<b><?=h(waktu($order['estimasi_selesai']))?></b></span></div><div class="divider-line"></div><h3>Catatan</h3><p><?=nl2br(h($order['catatan'] ?: 'Tidak ada catatan.'))?></p></section>
<aside class="admin-card detail-aside"><h2>Perbarui Status Laundry</h2><p class="inline-help">Perubahan status tersimpan ke database dan muncul di halaman cek status pelanggan.</p><form method="post"><?=csrf_input()?><div class="form-field"><label for="status">Pilih Status</label><select id="status" name="status" required><?php foreach(['Diterima','Diproses','Dikemas','Selesai'] as $state):?><option value="<?=h($state)?>" <?=($order['status']===$state)?'selected':''?>><?=h($state)?></option><?php endforeach;?></select></div><button class="btn login-submit" type="submit">Simpan Perubahan</button></form><div class="divider-line"></div><h2>Riwayat Proses</h2><ul class="history-list"><?php foreach($history as $s):?><li><strong><?=h($s['status_baru'])?></strong><small><?=h(waktu($s['berubah_pada']))?> • <?=h($s['pengelola']??'Sistem')?></small></li><?php endforeach;?></ul><?php if(!$history):?><p>Belum ada riwayat.</p><?php endif;?></aside></div>
<?php require __DIR__.'/../../includes/footer.php'; ?>
