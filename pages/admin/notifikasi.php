<?php
require __DIR__.'/../../includes/app.php'; require_admin();
if($_SERVER['REQUEST_METHOD']==='POST') {
    validate_csrf();
    $id=(int)($_POST['id']??0);
    $q=db()->prepare("UPDATE notifikasi SET status_pengiriman='Disalin' WHERE id_notifikasi=? AND status_pengiriman='Disiapkan'");$q->execute([$id]);
    flash('success','Pemberitahuan ditandai sudah disalin. Pengiriman WhatsApp tetap dilakukan manual.');redirect_to('notifikasi.php');
}
$rows=db()->query('SELECT n.id_notifikasi,n.pesan,n.status_pengiriman,n.dibuat_pada,o.kode_pesanan,p.nama AS pelanggan FROM notifikasi n JOIN pesanan o ON o.id_pesanan=n.id_pesanan JOIN pelanggan p ON p.id_pelanggan=o.id_pelanggan ORDER BY n.id_notifikasi DESC LIMIT 100')->fetchAll();
admin_page_head('Pemberitahuan Selesai','Salin teks WhatsApp dan kirim manual kepada pelanggan.');
?>
<section class="admin-card"><h2>Pesan yang Disiapkan</h2><p class="inline-help">Ini bukan integrasi WhatsApp otomatis. Status "Disalin" hanya catatan internal dan tidak membuktikan pesan sudah terkirim.</p>
<?php foreach($rows as $n):?><article class="notice-item"><div class="notice-head"><strong><?=h($n['kode_pesanan'].' • '.$n['pelanggan'])?></strong><span class="date-badge"><?=h($n['status_pengiriman'])?></span></div><textarea id="pesan-<?=(int)$n['id_notifikasi']?>" readonly rows="3"><?=h($n['pesan'])?></textarea><div class="notice-actions"><button type="button" class="btn btn-secondary btn-sm" data-copy-target="pesan-<?=(int)$n['id_notifikasi']?>">Salin Pesan</button><?php if($n['status_pengiriman']==='Disiapkan'):?><form method="post"><?=csrf_input()?><input type="hidden" name="id" value="<?=(int)$n['id_notifikasi']?>"><button class="btn btn-sm" type="submit">Tandai Sudah Disalin</button></form><?php endif;?></div></article><?php endforeach;?>
<?php if(!$rows):?><p>Belum ada pemberitahuan. Pesan akan disiapkan saat status pesanan diperbarui menjadi Selesai.</p><?php endif;?></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
