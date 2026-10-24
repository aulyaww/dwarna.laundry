-- Untuk database dwarna_laundry lokal yang SUDAH ada dan punya satu akun pemilik.
-- Jalankan SATU KALI setelah backup. Tidak menghapus akun atau data pesanan.
-- Password lama tidak berubah sampai diganti lewat halaman "Akun Admin".
USE `dwarna_laundry`;

-- Tahap aman: kenalkan nilai admin dahulu agar peran lama tetap valid sementara.
ALTER TABLE `pengelola`
  MODIFY COLUMN `peran` ENUM('pemilik','karyawan','admin') NOT NULL DEFAULT 'admin';

-- Ubah label akun yang sudah ada, tanpa mengganti id_pengelola atau password_hash.
UPDATE `pengelola` SET `nama`='Admin', `peran`='admin';

-- Satu jenis peran saja pada sistem final.
ALTER TABLE `pengelola`
  MODIFY COLUMN `nama` VARCHAR(100) NOT NULL DEFAULT 'Admin',
  MODIFY COLUMN `peran` ENUM('admin') NOT NULL DEFAULT 'admin';
