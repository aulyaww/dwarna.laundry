<?php
require __DIR__.'/../../includes/app.php'; require_admin();
$orders=db()->query('SELECT o.id_pesanan,o.kode_pesanan,o.status,o.tanggal_masuk,p.nama AS pelanggan,l.nama_layanan FROM pesanan o JOIN pelanggan p ON o.id_pelanggan=p.id_pelanggan JOIN layanan l ON o.id_layanan=l.id_layanan ORDER BY o.tanggal_masuk DESC,o.id_pesanan DESC')->fetchAll();
admin_page_head('Data Pesanan','Cari dan pantau pesanan laundry yang tersimpan di MySQL.');
?>
<div class="page-quick-action"><a class="btn btn-sm" href="tambah-pesanan.php">+ Tambah Pesanan</a></div>
<section class="admin-card"><div class="filter-row"><div class="search-input">⌕ &nbsp;<input data-filter-search type="search" placeholder="Cari kode, pelanggan, atau layanan" aria-label="Cari pesanan"></div><select data-filter-status aria-label="Filter status"><option value="">Semua Status</option><option>Diterima</option><option>Diproses</option><option>Dikemas</option><option>Selesai</option></select><span class="date-badge"><?=count($orders)?> pesanan</span></div><div class="table-wrap"><table class="data-table" id="demo-orders"><thead><tr><th>Kode</th><th>Pelanggan</th><th>Layanan</th><th>Status</th><th>Tanggal Masuk</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($orders as $o):?><tr data-status="<?=h($o['status'])?>"><td class="code-cell"><?=h($o['kode_pesanan'])?></td><td><?=h($o['pelanggan'])?></td><td><?=h($o['nama_layanan'])?></td><td><?=status_badge($o['status'])?></td><td><?=h(waktu($o['tanggal_masuk']))?></td><td><a class="action-link" href="detail-pesanan.php?id=<?=(int)$o['id_pesanan']?>">Lihat detail →</a></td></tr><?php endforeach;?>
<?php if(!$orders):?><tr><td colspan="6">Belum ada pesanan.</td></tr><?php endif;?>
</tbody></table></div></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
