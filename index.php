<?php $pageTitle='Beranda'; $rootPrefix=''; require __DIR__.'/includes/header.php'; ?>
<section class="home-hero">
 <div class="hero-img" role="img" aria-label="Foto ilustrasi tumpukan laundry bersih"></div><div class="hero-color"></div><img class="hero-local-art" src="assets/images/products/towels-illustration.svg" alt="Ilustrasi lipatan handuk merah dan putih">
 <div class="container hero-inside"><div class="hero-copy"><span class="hero-kicker">✦ &nbsp; Selamat Datang di dWarna Laundry</span>
 <h1>Laundry Bersih,<br>Cepat dan <em>Terpercaya</em></h1>
 <p>Solusi praktis untuk pakaian Anda setiap hari. Didukung aktivitas Anda dengan layanan laundry berkualitas, rapi, dan terpercaya.</p>
 <div class="hero-actions"><a href="#layanan" class="btn btn-light">Lihat Layanan &nbsp;→</a><a href="pages/cara-order.php" class="btn btn-outline-light">Cara Order</a></div></div>
 <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
</section>
<section class="benefits container" aria-label="Keunggulan dWarna Laundry">
 
<div class="benefit">
    <span class="benefit-ico">
        <i class="fa-solid fa-soap"></i>
    </span>
    <div>
        <b>Bersih & Rapi</b>
        <small>Hasil maksimal</small>
    </div>
</div>

<div class="benefit">
    <span class="benefit-ico">
        <i class="fa-solid fa-tag"></i>
    </span>
    <div>
        <b>Harga Terjangkau</b>
        <small>Sesuai kualitas</small>
    </div>
</div>

<div class="benefit">
    <span class="benefit-ico">
        <i class="fa-solid fa-stopwatch"></i>
    </span>
    <div>
        <b>Proses Cepat</b>
        <small>Hemat waktu</small>
    </div>
</div>

<div class="benefit">
    <span class="benefit-ico">
        <i class="fa-solid fa-shield-halved"></i>
    </span>
    <div>
        <b>Aman & Terpercaya</b>
        <small>Pakaian lebih terjaga</small>
    </div>
</div>

</section>
<section class="container services-section" id="layanan">
 <div class="section-heading"><div><span class="eyebrow">JENIS LAYANAN</span><h2>Layanan Kami</h2><p>Pilih layanan laundry sesuai kebutuhan Anda.</p></div>
<a href="pages/layanan.php" class="section-link">
    Lihat Semua &nbsp;→
</a>
</div>
 <div class="service-grid">
 <?php $services=[
  ['Reguler','Perawatan sehari-hari','service-reguler','<i class="fa-solid fa-shirt"></i>'],
  ['Express','Butuh lebih cepat','service-express','<i class="fa-solid fa-bolt"></i>'],
  ['Setrika Saja','Rapi dan siap dipakai','service-setrika','<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M3 15h18l-2 5H5l-2-5Z"/><path d="M4 15c1-5 4-8 10-8 4 0 6 3 6 8"/><path d="M8 8V5h5v2"/></svg>'],
  ['Bed Cover','Untuk perlengkapan tidur','service-bedcover','<i class="fa-solid fa-bed"></i>'],

 ]; foreach($services as $s): ?>
 <article class="service-card"><div class="service-photo <?= $s[2] ?>"><span class="service-overlay-icon"><?= $s[3] ?></span></div>
 <div class="service-info"><div><h3><?= $s[0] ?></h3><p><?= $s[1] ?></p></div><a href="pages/kontak.php" class="circle-link" aria-label="Info layanan <?= $s[0] ?>">→</a></div><p class="service-price">Harga sesuai konfirmasi mitra</p></article>
 <?php endforeach; ?>
 </div>
</section>
<section class="container home-process"><div><span class="eyebrow">KENAPA DWARNA?</span><h2>Laundry nyaman, tanpa ribet</h2><p>Mulai dari pemesanan hingga pengambilan, kami ingin membantu Anda memantau proses laundry dengan lebih mudah.</p><a href="pages/cara-order.php" class="btn">Pelajari Cara Order &nbsp;→</a></div><div class="process-visual"><span class="process-bubble b1">01<br><small>Hubungi</small></span><span class="process-bubble b2">02<br><small>Serahkan</small></span><span class="process-bubble b3">03<br><small>Pantau</small></span><span class="process-bubble b4">04<br><small>Selesai</small></span></div></section>
<section class="cta-band"><div class="container cta-inner"><div><span class="eyebrow">MUDAH & PRAKTIS</span><h2>Ingin tahu status laundry kamu?</h2><p>Pantau proses laundry melalui halaman cek status pesanan.</p></div><a href="pages/cek-status.php" class="btn btn-light">Cek Status &nbsp;→</a></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
