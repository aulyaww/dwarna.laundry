<?php if ($isAdmin && !$isLogin): ?>
<aside class="sidebar" id="admin-sidebar">
 <a class="sidebar-brand" href="<?= $rootPrefix ?>index.php"><img src="<?= $rootPrefix ?>assets/images/logo/logo-mitra.jpg" alt=""/><span><b>dWarna</b><small>Laundry</small></span></a>
 <p class="sidebar-label">MENU UTAMA</p>
 <nav aria-label="Navigasi pengelola" class="sidebar-nav">
 
<?php
$here = basename($_SERVER['SCRIPT_NAME'] ?? '');

$menu = [
    ['dashboard.php', '<i class="fa-solid fa-house"></i>', 'Dashboard'],
    ['pesanan.php', '<i class="fa-solid fa-bag-shopping"></i>', 'Pesanan'],
    ['pelanggan.php', '<i class="fa-solid fa-users"></i>', 'Pelanggan'],
    ['riwayat.php', '<i class="fa-solid fa-clock-rotate-left"></i>', 'Riwayat'],
    ['notifikasi.php', '<i class="fa-solid fa-bell"></i>', 'Pemberitahuan'],
    ['catatan-sementara.php', '<i class="fa-solid fa-clipboard"></i>', 'Catatan Sementara'],
    ['pengaturan-akun.php', '<i class="fa-solid fa-user-gear"></i>', 'Akun Admin']
];

 foreach($menu as $m): $active=($here===$m[0] || ($m[0]==='pesanan.php' && in_array($here,['detail-pesanan.php','tambah-pesanan.php'])) || ($m[0]==='pelanggan.php' && in_array($here,['tambah-pelanggan.php'],true))); ?>
 <a class="<?= $active?'active':'' ?>" href="<?= $rootPrefix ?>pages/admin/<?= $m[0] ?>"><span class="sidebar-ico"><?= $m[1] ?></span><?= $m[2] ?></a>
 <?php endforeach; ?>
 </nav>
 
<div class="sidebar-bottom">

    <!-- Ikon akun Admin -->
    <span class="demo-pill">
        <i class="fa-solid fa-user-shield"></i>
        &nbsp; Admin
    </span>

    <!-- Kembali ke halaman pelanggan -->
    <a href="<?= $rootPrefix ?>index.php">
        <i class="fa-solid fa-arrow-left"></i>
        &nbsp; Halaman Pelanggan
    </a>

    <!-- Tombol logout -->
    <form method="post"
          action="<?= $rootPrefix ?>pages/admin/logout.php">

        <?= csrf_input() ?>

        <button class="sidebar-logout" type="submit">
            <i class="fa-solid fa-right-from-bracket"></i>
            &nbsp; Keluar Akun
        </button>

    </form>

</div>

</aside><div class="sidebar-shade" data-sidebar-shade></div>
<?php elseif(!$isLogin): ?>
<header class="site-header"><div class="container nav-row">
 <a class="brand" href="<?= $rootPrefix ?>index.php"><img src="<?= $rootPrefix ?>assets/images/logo/logo-mitra.jpg" alt="Logo dWarna"/><span><b>dWarna</b><small>Laundry</small></span></a>
 <button type="button" class="nav-toggle" aria-expanded="false" aria-controls="public-navigation" data-menu-toggle>☰</button>
 <nav id="public-navigation" class="nav-links" aria-label="Navigasi pelanggan">
  <a class="<?= ($pageTitle==='Beranda')?'current':'' ?>" href="<?= $rootPrefix ?>index.php">Beranda</a> 
  <a class="<?= ($pageTitle==='Layanan')?'current':'' ?>" href="<?= $rootPrefix ?>pages/layanan.php">Layanan</a>
  <a class="<?= ($pageTitle==='Cara Order')?'current':'' ?>" href="<?= $rootPrefix ?>pages/cara-order.php">Cara Order</a>
  <a class="<?= ($pageTitle==='Cek Status')?'current':'' ?>" href="<?= $rootPrefix ?>pages/cek-status.php">Cek Status</a>
  <a class="<?= ($pageTitle==='Kontak')?'current':'' ?>" href="<?= $rootPrefix ?>pages/kontak.php">Kontak</a>

<a class="btn btn-sm nav-cta" href="<?= $rootPrefix ?>pages/kontak.php">
    <i class="fa-solid fa-comment-dots"></i>
    Pesan Sekarang
</a>
 </nav>
</div></header>
<?php endif; ?>
