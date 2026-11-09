
<?php
require __DIR__.'/../../includes/app.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$order = $id > 0 ? query_order($id) : null;

if (!$order) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}

$nota = "dWARNA LAUNDRY PALOPO\n"
    . "NOTA PESANAN\n"
    . "------------------------------\n"
    . "No. Nota: {$order['kode_pesanan']}\n"
    . "Tanggal: " . waktu($order['tanggal_masuk']) . "\n"
    . "Nama: {$order['pelanggan_nama']}\n"
    . "WhatsApp: {$order['no_whatsapp']}\n"
    . "------------------------------\n"
    . "Layanan: {$order['nama_layanan']}\n"
    . "Jumlah: {$order['jumlah']} {$order['satuan']}\n"
    . "Estimasi Harga: " . rupiah($order['total_harga']) . "\n"
    . "Estimasi Selesai: " . waktu($order['estimasi_selesai']) . "\n"
    . "Catatan: " . ($order['catatan'] ?: '-') . "\n"
    . "------------------------------\n"
    . "CEK STATUS LAUNDRY\n"
    . "http://localhost/dwarna-laundry/pages/cek-status.php\n\n"
    . "Kode Pesanan: {$order['kode_pesanan']}\n"
    . "Gunakan nomor WhatsApp yang terdaftar.\n\n"
    . "Terima kasih telah mempercayakan laundry Anda kepada dWarna Laundry!";

admin_page_head('Nota Pesanan', 'Rincian nota berdasarkan data pesanan.');
?>

<div class="page-quick-action">
    <a class="section-link" href="detail-pesanan.php?id=<?= $id ?>">
        ← Kembali ke Detail Pesanan
    </a>
</div>

<section class="admin-card form-width">
    <h2>Nota Pesanan</h2>

    <pre style="white-space:pre-wrap;overflow-wrap:anywhere;font-family:inherit;line-height:1.8;"><?= h($nota) ?></pre>
    
<button type="button" class="btn" id="salin-nota">
    <i class="fa-solid fa-copy"></i> Salin Nota
</button>

<?php
$nomorWA = preg_replace('/\D+/', '', (string)$order['no_whatsapp']);

if (str_starts_with($nomorWA, '0')) {
    $nomorWA = '62' . substr($nomorWA, 1);
} elseif (str_starts_with($nomorWA, '8')) {
    $nomorWA = '62' . $nomorWA;
}

$linkWA = 'https://wa.me/' . $nomorWA . '?text=' . rawurlencode($nota);
?>

<?php if (preg_match('/^62[0-9]{8,13}$/', $nomorWA)): ?>
    <a href="<?= h($linkWA) ?>"
       class="btn"
       style="background:#16834a;"
       target="_blank"
       rel="noopener noreferrer">
        <i class="fa-brands fa-whatsapp"></i>
        Kirim via WhatsApp
    </a>
<?php else: ?>
    <p class="inline-help">
        Nomor WhatsApp pelanggan tidak valid.
    </p>
<?php endif; ?>


<p id="pesan-salin" class="inline-help" role="status"></p>

<script>
document.getElementById('salin-nota').addEventListener('click', async function () {
    const isiNota = document.querySelector('.admin-card.form-width pre').textContent;
    const pesan = document.getElementById('pesan-salin');

    try {
        await navigator.clipboard.writeText(isiNota);
        pesan.textContent = 'Nota berhasil disalin! Silakan tempel ke WhatsApp pelanggan.';
    } catch (error) {
        pesan.textContent = 'Gagal menyalin otomatis. Silakan salin teks nota secara manual.';
    }
});
</script>

</section>

<?php require __DIR__.'/../../includes/footer.php'; ?>
