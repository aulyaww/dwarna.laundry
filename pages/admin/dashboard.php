<?php
require __DIR__.'/../../includes/app.php'; require_admin();
$counts=['Diterima'=>0,'Diproses'=>0,'Dikemas'=>0,'Selesai'=>0];
foreach(db()->query('SELECT status,COUNT(*) total FROM pesanan GROUP BY status') as $r) $counts[$r['status']]=(int)$r['total'];
$latest=db()->query('SELECT o.id_pesanan,o.kode_pesanan,o.status,o.tanggal_masuk,p.nama AS pelanggan,l.nama_layanan FROM pesanan o JOIN pelanggan p ON o.id_pelanggan=p.id_pelanggan JOIN layanan l ON o.id_layanan=l.id_layanan ORDER BY o.tanggal_masuk DESC,o.id_pesanan DESC LIMIT 8')->fetchAll();
admin_page_head('Dashboard','Pantau pesanan laundry berdasarkan data yang tersimpan di MySQL.');
?>

<div class="stat-grid">

<?php foreach([
    ['Diterima', 'stat-rose', 'fa-clipboard-check'],
    ['Diproses', 'stat-amber', 'fa-gears'],
    ['Dikemas', 'stat-blue', 'fa-box'],
    ['Selesai', 'stat-green', 'fa-circle-check']
] as [$status, $sty, $ico]): ?>

    <div class="stat-card <?= h($sty) ?>">

        <div class="stat-head">
            <span>
                <i class="fa-solid <?= h($ico) ?>"></i>
            </span>
            <span><?= h($status) ?></span>
        </div>

        <div class="stat-value">
            <?= (int)$counts[$status] ?>
        </div>

        <small>Pesanan di database</small>

    </div>

<?php endforeach; ?>

</div>

<section class="admin-card"><div class="admin-card-head"><div><h2>Pesanan Terbaru</h2><p>Terhubung langsung ke database dWarna Laundry</p></div><a class="section-link" href="pesanan.php">Lihat Semua →</a></div>
<div class="table-wrap"><table class="data-table"><thead><tr><th>Kode</th><th>Pelanggan</th><th>Layanan</th><th>Status</th><th>Tanggal Masuk</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($latest as $o):?><tr><td class="code-cell"><?=h($o['kode_pesanan'])?></td><td><?=h($o['pelanggan'])?></td><td><?=h($o['nama_layanan'])?></td><td><?=status_badge($o['status'])?></td><td><?=h(waktu($o['tanggal_masuk']))?></td><td><a class="action-link" href="detail-pesanan.php?id=<?=(int)$o['id_pesanan']?>">Lihat detail →</a></td></tr><?php endforeach;?>
<?php if (!$latest):?><tr><td colspan="6">Belum ada pesanan. Tambahkan melalui menu Pesanan.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require __DIR__.'/../../includes/footer.php'; ?>
