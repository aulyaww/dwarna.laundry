-- V3.3: jalankan satu kali pada database LAMA (sudah ada 7 tabel).
-- Database tetap 7 tabel. Hanya menambah kolom aktif di tabel pelanggan.
-- Semua pelanggan yang sudah ada akan bernilai aktif=1.
-- Dapat diulang tanpa menambah kolom ganda.
USE dwarna_laundry;
SET @kolom_ada = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME='pelanggan' AND COLUMN_NAME='aktif');
SET @perintah = IF(@kolom_ada = 0,
  'ALTER TABLE `pelanggan` ADD COLUMN `aktif` TINYINT(1) NOT NULL DEFAULT 1',
  'SELECT ''Kolom aktif sudah tersedia'' AS hasil');
PREPARE stmt_arsip_pelanggan FROM @perintah;
EXECUTE stmt_arsip_pelanggan;
DEALLOCATE PREPARE stmt_arsip_pelanggan;
