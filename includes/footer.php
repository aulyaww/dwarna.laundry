<?php
// Variabel tampilan diberi nilai bawaan karena footer juga dipanggil dari halaman admin.
$isAdmin = $isAdmin ?? false;
$isLogin = $isLogin ?? false;
$rootPrefix = $rootPrefix ?? '';
if ($isAdmin && !$isLogin): ?>

</div></div>
<?php elseif($isLogin): ?>
</main>
<?php else: ?>
</main><footer class="footer"><div class="container footer-grid">
 <div><a class="brand footer-brand" href="<?= $rootPrefix ?>index.php"><img src="<?= $rootPrefix ?>assets/images/logo/logo-mitra.jpg" alt=""/><span><b>dWarna</b><small>Laundry</small></span></a><p>Laundry bersih, cepat, dan terpercaya. Solusi praktis perawatan pakaian Anda di Palopo.</p></div>
 <div><h4>Jelajahi</h4><a href="<?= $rootPrefix ?>index.php">Beranda</a><a href="<?= $rootPrefix ?>pages/layanan.php">Layanan</a><a href="<?= $rootPrefix ?>pages/cara-order.php">Cara Order</a></div>
 <div><h4>Bantuan</h4><a href="<?= $rootPrefix ?>pages/cek-status.php">Cek Status</a><a href="<?= $rootPrefix ?>pages/kontak.php">Kontak</a><a href="<?= $rootPrefix ?>pages/admin/login.php">Login Pengelola</a></div>
 <div><h4>Alamat</h4><p>Palopo, Sulawesi Selatan<br><small>Jl. Tociung, Kel. Surutanga, Kec Wara Timur.</small></p></div>
 </div><div class="container footer-bottom"><span>© <?= date('Y') ?> dWarna Laundry Palopo.</span></div></footer>
<?php endif; ?><script src="<?= $rootPrefix ?>assets/js/main.js?v=99"></script></body></html>
