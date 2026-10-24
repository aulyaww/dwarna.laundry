<?php
require __DIR__.'/../includes/app.php';
$order=null;$message='';$searched=false;
if(isset($_POST['kode']) || isset($_POST['nomor'])) {
    $searched=true;
    $code=trim((string)($_POST['kode']??''));
    $whatsapp=trim((string)($_POST['nomor']??''));
    if(!preg_match('/^[A-Za-z0-9-]{4,30}$/',$code)||!preg_match('/^\+?[0-9]{9,16}$/',$whatsapp))$message='Masukkan kode pesanan dan nomor WhatsApp yang valid.';
    else {
        $query=db()->prepare('SELECT o.kode_pesanan,o.status,o.tanggal_masuk,o.estimasi_selesai,p.nama AS pelanggan,l.nama_layanan FROM pesanan o JOIN pelanggan p ON p.id_pelanggan=o.id_pelanggan JOIN layanan l ON l.id_layanan=o.id_layanan WHERE o.kode_pesanan=? AND p.no_whatsapp=? LIMIT 1');
        $query->execute([$code,$whatsapp]);$order=$query->fetch() ?: null;
        if(!$order)$message='Pesanan tidak ditemukan. Pastikan kode dan nomor WhatsApp sama seperti data dari laundry.';
    }
}
$pageTitle='Cek Status';$rootPrefix='../';require __DIR__.'/../includes/header.php';
?>
<section class="track-hero"><div class="container track-hero-inner"><div class="track-sparkle">✦</div><span class="hero-kicker">TRACKING PESANAN</span><h1>Cek Status Laundry</h1><p>Masukkan kode pesanan serta WhatsApp yang terdaftar untuk melihat proses laundry.</p><form class="tracking-form" method="post"><label class="sr-only" for="kode">Kode Pesanan</label><input id="kode" name="kode" type="text" maxlength="30" required placeholder="Contoh: DW0001" value="<?=h($_POST['kode']??'')?>"><label class="sr-only" for="nomor">Nomor WhatsApp</label><input id="nomor" name="nomor" type="tel" required maxlength="20" placeholder="Nomor WhatsApp untuk verifikasi" value="<?=h($_POST['nomor']??'')?>"><button type="submit">⌕ &nbsp; Cek Status</button></form><small class="tracking-help">Tanpa login. Hasil hanya ditampilkan jika kode dan nomor WhatsApp cocok.</small></div></section>
<div class="container track-body">
<?php if($message):?><div role="status" class="app-alert alert-error"><?=h($message)?></div><?php elseif(!$searched):?><div class="app-alert alert-info">Status pesanan akan tampil setelah kamu memasukkan kode dan nomor WhatsApp yang sesuai.</div><?php endif; ?>
<?php if($order):?><div class="tracking-detail">
<?php
$fotoPerLayanan = [
    'reguler' => 'reguler.jpg',
    'express' => 'express.jpg',
    'cuci komplit' => 'cuci-komplit.jpg',
    'bed cover' => 'bed-cover.jpg',
    'sepatu' => 'sepatu.jpg',
    'kemeja/batik' => 'kemeja-batik.jpg',
    'kemeja / batik' => 'kemeja-batik.jpg',
    'selimut' => 'selimut.jpg',
    'karpet' => 'karpet.jpg',
    'setrika saja' => 'setrika.jpg'
];

$namaLayanan = strtolower(trim($order['nama_layanan']));

$foto = $fotoPerLayanan[$namaLayanan] ?? null;

$fotoAda = $foto && is_file(
    __DIR__ . '/../assets/images/mitra/' . $foto
);
?>

<div
    class="tracking-photo"
    style="background: <?= $fotoAda
        ? "url('../assets/images/mitra/{$foto}') center / cover no-repeat"
        : "#f4eeee"
    ?> !important;"
></div>
<div class="tracking-info"><h3>Detail Pesanan <?=status_badge($order['status'])?></h3><div class="kv-grid"><span>Kode Pesanan<b><?=h($order['kode_pesanan'])?></b></span><span>Nama Pelanggan<b><?=h($order['pelanggan'])?></b></span><span>Layanan<b><?=h($order['nama_layanan'])?></b></span><span>Masuk<b><?=h(waktu($order['tanggal_masuk']))?></b></span><span>Perkiraan Selesai<b><?=h(waktu($order['estimasi_selesai']))?></b></span></div></div></div><div class="tracking-stage"><h3>Status Laundry</h3><div class="status-timeline"><?php $states=['Diterima','Diproses','Dikemas','Selesai'];$pos=array_search($order['status'],$states,true);foreach($states as $i=>$state):?><div class="phase <?=($i<=$pos)?'done':''?>"><span><?=($i<=$pos)?'✓':'◌'?></span><b><?=h($state)?></b><small><?=($i<=$pos)?'Tahap tercapai':'Menunggu'?></small></div><?php endforeach;?></div></div><?php endif; ?>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
