
<?php
$pageTitle = 'Layanan';
$rootPrefix = '../';

require __DIR__ . '/../includes/header.php';

/*
 * DAFTAR LAYANAN DWARNA LAUNDRY
 *
 * Foto disimpan di:
 * assets/images/mitra/
 *
 * Harga dan durasi di bawah mengikuti mockup Figma.
 * Sesuaikan setelah dikonfirmasi dengan mitra.
 */

$layanan = [
    [
        'nama' => 'Reguler',
        'durasi' => '1 hari',
        'harga' => 'Rp 8.500/kg',
        'foto' => 'reguler.jpg',
        'ikon' => 'fa-shirt'
    ],
    [
        'nama' => 'Express',
        'durasi' => '2 - 8 jam',
        'harga' => 'Rp 25.000/kg',
        'foto' => 'express.jpg',
        'ikon' => 'fa-bolt'
    ],
    [
        'nama' => 'Cuci Komplit',
        'durasi' => '1 hari',
        'harga' => 'Rp 10.500/kg',
        'foto' => 'cuci-komplit.jpg',
        'ikon' => 'fa-soap'
    ],
    [
        'nama' => 'Bed Cover',
        'durasi' => '2 hari',
        'harga' => 'Mulai Rp 35.000',
        'foto' => 'bed-cover.jpg',
        'ikon' => 'fa-bed'
    ],
    [
        'nama' => 'Sepatu',
        'durasi' => '2 hari',
        'harga' => 'Mulai Rp 50.000',
        'foto' => 'sepatu.jpg',
        'ikon' => 'fa-shoe-prints'
    ],
    [
        'nama' => 'Kemeja / Batik',
        'durasi' => '1 hari',
        'harga' => 'Mulai Rp 20.000',
        'foto' => 'kemeja-batik.jpg',
        'ikon' => 'fa-shirt'
    ],
    [
        'nama' => 'Selimut',
        'durasi' => '2 hari',
        'harga' => 'Mulai Rp 20.000',
        'foto' => 'selimut.jpg',
        'ikon' => 'fa-layer-group'
    ],
    [
        'nama' => 'Karpet',
        'durasi' => '5 hari',
        'harga' => 'Rp 35.000/meter',
        'foto' => 'karpet.jpg',
        'ikon' => 'fa-rug'
    ]
];
?>

<section class="container services-section">

    <div class="section-heading">
        <div>
            <h2>Layanan Kami</h2>
            <p>Pilih layanan laundry sesuai kebutuhan Anda.</p>
        </div>
    </div>

    <div class="service-grid">

        <?php foreach ($layanan as $item): ?>

            <article class="service-card">

                <div
                    class="service-photo"
                    style="background-image: url('../assets/images/mitra/<?= htmlspecialchars($item['foto'], ENT_QUOTES, 'UTF-8') ?>');"
                >
                    <span class="service-overlay-icon">
                        <i class="fa-solid <?= htmlspecialchars($item['ikon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                    </span>
                </div>

                <div class="service-info">
                    <div>
                        <h3><?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($item['durasi'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <a
                        href="kontak.php"
                        class="circle-link"
                        aria-label="Tanya layanan <?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?>"
                    >→</a>
                </div>

                <p class="service-price">
                    <?= htmlspecialchars($item['harga'], ENT_QUOTES, 'UTF-8') ?>
                </p>

            </article>

        <?php endforeach; ?>

    </div>

    <div style="margin-top: 35px;">
        <a href="../index.php" class="section-link">
            ← Kembali ke Beranda
        </a>
    </div>

</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
