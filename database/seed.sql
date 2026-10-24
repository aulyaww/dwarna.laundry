-- dWarna Laundry Palopo - DATA CONTOH untuk schema.sql final In-Scope (7 tabel)
-- Cocok dengan: pengelola, pelanggan, layanan, pesanan, riwayat_status, notifikasi, catatan_sementara.
-- Data SEMU untuk testing, bukan data operasional mitra atau ERD Tugas 4 final.
-- HANYA untuk instalasi baru yang masih KOSONG; JANGAN import pada database yang sudah berisi data.
-- Jangan gunakan seed.sql dan migrasi_01_layanan.sql VERSI LAMA bersama file ini.
USE `dwarna_laundry`;

START TRANSACTION;

-- 1. Data master layanan sesuai kartu layanan rancangan high-fidelity.
INSERT INTO layanan (nama_layanan, harga_satuan, satuan) VALUES
  ('Reguler', 5000.00, 'kg'),
  ('Express', 8000.00, 'kg'),
  ('Setrika Saja', 4000.00, 'kg'),
  ('Bed Cover', 20000.00, 'pcs');

-- 2. Pelanggan dummy, nomor WhatsApp bukan nomor pelanggan sebenarnya.
INSERT INTO pelanggan (nama, no_whatsapp, alamat) VALUES
  ('Aulia (Contoh)', '081200000001', 'Palopo - Data simulasi'),
  ('Budi (Contoh)', '081300000002', 'Palopo - Data simulasi'),
  ('Citra (Contoh)', '081400000003', 'Palopo - Data simulasi'),
  ('Deni (Contoh)', '081500000004', 'Palopo - Data simulasi');

-- 3. Pesanan dengan foreign key berdasarkan ID yang ditemukan lewat lookup.
INSERT INTO pesanan (kode_pesanan, id_pelanggan, id_layanan, jumlah, total_harga, tanggal_masuk, estimasi_selesai, status, catatan)
SELECT 'DW0001', p.id_pelanggan, l.id_layanan, 5.00, 25000.00, '2026-10-10 09:00:00', '2026-10-12 17:00:00', 'Diterima', 'Data simulasi'
FROM pelanggan p JOIN layanan l ON l.nama_layanan = 'Reguler'
WHERE p.no_whatsapp = '081200000001';

INSERT INTO pesanan (kode_pesanan, id_pelanggan, id_layanan, jumlah, total_harga, tanggal_masuk, estimasi_selesai, status, catatan)
SELECT 'DW0002', p.id_pelanggan, l.id_layanan, 5.00, 40000.00, '2026-10-10 10:00:00', '2026-10-11 17:00:00', 'Diproses', 'Data simulasi'
FROM pelanggan p JOIN layanan l ON l.nama_layanan = 'Express'
WHERE p.no_whatsapp = '081300000002';

INSERT INTO pesanan (kode_pesanan, id_pelanggan, id_layanan, jumlah, total_harga, tanggal_masuk, estimasi_selesai, status, catatan)
SELECT 'DW0003', p.id_pelanggan, l.id_layanan, 3.00, 12000.00, '2026-10-11 08:00:00', '2026-10-12 17:00:00', 'Dikemas', 'Data simulasi'
FROM pelanggan p JOIN layanan l ON l.nama_layanan = 'Setrika Saja'
WHERE p.no_whatsapp = '081400000003';

INSERT INTO pesanan (kode_pesanan, id_pelanggan, id_layanan, jumlah, total_harga, tanggal_masuk, estimasi_selesai, status, catatan)
SELECT 'DW0004', p.id_pelanggan, l.id_layanan, 1.00, 20000.00, '2026-10-09 09:00:00', '2026-10-10 17:00:00', 'Selesai', 'Data simulasi'
FROM pelanggan p JOIN layanan l ON l.nama_layanan = 'Bed Cover'
WHERE p.no_whatsapp = '081500000004';

-- 4. Riwayat status dummy; id_pengelola dibiarkan NULL karena belum ada akun aktif.
INSERT INTO riwayat_status (id_pesanan, status_baru)
SELECT id_pesanan, status FROM pesanan WHERE kode_pesanan IN ('DW0001','DW0002','DW0003','DW0004');

COMMIT;

-- Tabel pengelola sengaja tidak diisi. Akun Admin dikonfigurasi secara terpisah.
