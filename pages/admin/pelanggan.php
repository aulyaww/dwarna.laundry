<?php
/**
 * dWarna Laundry - Daftar pelanggan + Edit dan Hapus.
 *
 * - Edit nama, nomor WhatsApp, dan alamat melalui formulir di halaman ini.
 * - Hapus hanya boleh untuk pelanggan yang belum pernah mempunyai pesanan.
 * - Semua perubahan menggunakan POST, CSRF, dan prepared statement.
 * - Tidak menambah/mengubah tabel database, CSS, login, atau fitur arsip.
 */
require __DIR__ . '/../../includes/app.php';
require_admin();

$errors = [];
$editId = 0;
$inputEdit = null;

// Proses tombol Edit atau Hapus sebelum ada HTML yang dikirim ke browser.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();

    $aksi = (string)($_POST['aksi'] ?? '');
    $id = filter_var($_POST['id_pelanggan'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);
    if (!$id || !in_array($aksi, ['edit', 'hapus'], true)) {
        flash('error', 'Permintaan tidak valid. Silakan coba lagi.');
        redirect_to('pelanggan.php');
    }

    if ($aksi === 'hapus') {
        try {
            db()->beginTransaction();

            // Kunci baris agar permintaan bersamaan tidak mengubah hasil pengecekan.
            $cek = db()->prepare('SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = ? FOR UPDATE');
            $cek->execute([$id]);
            if (!$cek->fetch()) {
                db()->rollBack();
                flash('error', 'Pelanggan tidak ditemukan.');
                redirect_to('pelanggan.php');
            }

            $cekPesanan = db()->prepare('SELECT COUNT(*) FROM pesanan WHERE id_pelanggan = ?');
            $cekPesanan->execute([$id]);
            if ((int)$cekPesanan->fetchColumn() > 0) {
                db()->rollBack();
                flash('error', 'Tidak bisa menghapus pelanggan yang sudah memiliki pesanan. Riwayat harus dipertahankan.');
                redirect_to('pelanggan.php');
            }

            $hapus = db()->prepare('DELETE FROM pelanggan WHERE id_pelanggan = ?');
            $hapus->execute([$id]);
            db()->commit();
            flash('success', 'Data pelanggan berhasil dihapus.');
        } catch (Throwable $e) {
            if (db()->inTransaction()) db()->rollBack();
            flash('error', 'Gagal menghapus pelanggan. Data tidak diubah.');
        }
        redirect_to('pelanggan.php');
    }

    // Proses menyimpan hasil Edit.
    $editId = (int)$id;
    $nama = trim((string)($_POST['nama'] ?? ''));
    $wa = trim((string)($_POST['no_whatsapp'] ?? ''));
    $alamat = trim((string)($_POST['alamat'] ?? ''));
    $inputEdit = ['nama' => $nama, 'no_whatsapp' => $wa, 'alamat' => $alamat];
    $panjang = static function (string $teks): int {
        return function_exists('mb_strlen') ? mb_strlen($teks, 'UTF-8') : strlen($teks);
    };

    if ($panjang($nama) < 2 || $panjang($nama) > 100) {
        $errors[] = 'Nama harus berisi 2 sampai 100 karakter.';
    }
    if (!preg_match('/^\+?[0-9]{9,16}$/', $wa)) {
        $errors[] = 'Nomor WhatsApp harus 9–16 digit dan boleh diawali +.';
    }
    if ($panjang($alamat) > 1000) {
        $errors[] = 'Alamat maksimal 1000 karakter.';
    }

    $cek = db()->prepare('SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = ?');
    $cek->execute([$editId]);
    if (!$cek->fetch()) {
        flash('error', 'Pelanggan tidak ditemukan.');
        redirect_to('pelanggan.php');
    }

    if (!$errors) {
        try {
            $simpan = db()->prepare('UPDATE pelanggan SET nama = ?, no_whatsapp = ?, alamat = ? WHERE id_pelanggan = ?');
            $simpan->execute([$nama, $wa, $alamat !== '' ? $alamat : null, $editId]);
            flash('success', 'Data pelanggan berhasil diperbarui.');
            redirect_to('pelanggan.php');
        } catch (Throwable $e) {
            $errors[] = 'Perubahan belum tersimpan. Silakan periksa data dan coba lagi.';
        }
    }
}

// Tampilan formulir ketika tombol Edit diklik.
if ($editId === 0 && isset($_GET['edit'])) {
    $editId = (int)($_GET['edit']);
    if ($editId <= 0) {
        flash('error', 'Pelanggan yang dipilih tidak valid.');
        redirect_to('pelanggan.php');
    }
}

$pelangganEdit = null;
if ($editId > 0) {
    $q = db()->prepare('SELECT id_pelanggan, nama, no_whatsapp, alamat FROM pelanggan WHERE id_pelanggan = ?');
    $q->execute([$editId]);
    $pelangganEdit = $q->fetch();
    if (!$pelangganEdit) {
        flash('error', 'Pelanggan tidak ditemukan.');
        redirect_to('pelanggan.php');
    }
    if ($inputEdit !== null) {
        $pelangganEdit = array_merge($pelangganEdit, $inputEdit);
    }
}

// Mempertahankan daftar dan pencarian pelanggan yang sudah ada.
$customers = db()->query(
    'SELECT p.id_pelanggan, p.nama, p.no_whatsapp, p.alamat,
        COUNT(o.id_pesanan) AS jumlah_pesanan
     FROM pelanggan p
     LEFT JOIN pesanan o ON o.id_pelanggan = p.id_pelanggan
     GROUP BY p.id_pelanggan, p.nama, p.no_whatsapp, p.alamat
     ORDER BY p.id_pelanggan DESC'
)->fetchAll();

admin_page_head('Data Pelanggan', 'Daftar pelanggan yang tersimpan di database.');
?>

<div class="page-quick-action">
    <a href="tambah-pelanggan.php" class="btn btn-sm">+ Tambah Pelanggan</a>
</div>

<?php if ($pelangganEdit): ?>
<section class="admin-card form-width" id="form-edit-pelanggan" style="margin-bottom:22px">
    <div class="admin-card-head">
        <div>
            <h2>Edit Pelanggan</h2>
            <p>Ubah informasi pelanggan, lalu klik Simpan Perubahan.</p>
        </div>
    </div>

    <?php foreach ($errors as $error): ?>
        <div class="app-alert alert-error" role="alert"><?= h($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="pelanggan.php#form-edit-pelanggan">
        <?= csrf_input() ?>
        <input type="hidden" name="aksi" value="edit">
        <input type="hidden" name="id_pelanggan" value="<?= (int)$pelangganEdit['id_pelanggan'] ?>">
        <div class="two-col-form">
            <div class="form-field">
                <label for="edit-nama">Nama Pelanggan</label>
                <input id="edit-nama" name="nama" required maxlength="100"
                       value="<?= h($pelangganEdit['nama']) ?>">
            </div>
            <div class="form-field">
                <label for="edit-wa">Nomor WhatsApp</label>
                <input id="edit-wa" name="no_whatsapp" required maxlength="20" inputmode="tel"
                       value="<?= h($pelangganEdit['no_whatsapp']) ?>">
            </div>
        </div>
        <div class="form-field">
            <label for="edit-alamat">Alamat (opsional)</label>
            <textarea id="edit-alamat" name="alamat" rows="3" maxlength="1000"><?= h($pelangganEdit['alamat'] ?? '') ?></textarea>
        </div>
        <p class="inline-help">Jika nomor WhatsApp diubah, pelanggan harus menggunakan nomor terbaru saat mengecek status pesanan.</p>
        <div class="form-actions">
            <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn">Simpan Perubahan</button>
        </div>
    </form>
</section>
<?php endif; ?>

<section class="admin-card">
    <div class="filter-row">
        <div class="search-input">
            ⌕ &nbsp;<input type="search" data-simple-filter placeholder="Cari nama atau WhatsApp" aria-label="Cari pelanggan">
        </div>
        <span class="date-badge"><?= count($customers) ?> pelanggan</span>
    </div>

    <div class="table-wrap">
        <table class="data-table simple-filter-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>WhatsApp</th>
                    <th>Alamat</th>
                    <th>Jumlah Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $p): ?>
                <tr>
                    <td><strong><?= h($p['nama']) ?></strong></td>
                    <td><?= h($p['no_whatsapp']) ?></td>
                    <td><?= h($p['alamat'] ?: '–') ?></td>
                    <td><?= (int)$p['jumlah_pesanan'] ?></td>
                    <td>
                        <div class="customer-action-row" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                            <a class="btn btn-secondary btn-sm customer-action"
                               href="pelanggan.php?edit=<?= (int)$p['id_pelanggan'] ?>#form-edit-pelanggan">Edit</a>

                            <form method="post" action="pelanggan.php" style="margin:0"
                                  onsubmit="return confirm('Hapus pelanggan ini secara permanen? Data yang dihapus tidak dapat dikembalikan.');">
                                <?= csrf_input() ?>
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_pelanggan" value="<?= (int)$p['id_pelanggan'] ?>">
                                <button type="submit" class="btn btn-secondary btn-sm customer-action customer-delete"
                                        style="color:#b42332;border-color:#efc2c9"
                                        <?= (int)$p['jumlah_pesanan'] > 0 ? 'disabled title="Tidak bisa dihapus karena sudah punya pesanan"' : '' ?>>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$customers): ?>
                    <tr><td colspan="5">Belum ada data pelanggan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="inline-help" style="margin-top:16px">
        Tombol Hapus hanya aktif untuk pelanggan yang belum memiliki pesanan, agar riwayat laundry tetap aman.
    </p>
</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
