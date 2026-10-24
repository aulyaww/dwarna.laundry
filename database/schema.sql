-- Skema pengembangan In-Scope dWarna Laundry Palopo: 7 tabel.
-- V3.3: kolom aktif di pelanggan untuk arsip tanpa menghapus riwayat pesanan.
-- Mengikuti kesepakatan In-Scope terbaru. Satu akun Admin bersama.
-- 'nama' tetap tersimpan untuk kompatibilitas relasi dan riwayat, nilainya selalu Admin.
-- Simpan sebagai dokumentasi proyek dan untuk instalasi database BARU saja.
-- JANGAN IMPORT ULANG pada database lokal yang sudah berisi data.
CREATE DATABASE IF NOT EXISTS dwarna_laundry
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dwarna_laundry;

CREATE TABLE IF NOT EXISTS pengelola (
  id_pengelola INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL DEFAULT 'Admin',
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  peran ENUM('admin') NOT NULL DEFAULT 'admin',
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pelanggan (
  id_pelanggan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_whatsapp VARCHAR(20) NOT NULL,
  alamat TEXT NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS layanan (
  id_layanan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_layanan VARCHAR(100) NOT NULL,
  harga_satuan DECIMAL(12,2) NOT NULL DEFAULT 0,
  satuan VARCHAR(20) NOT NULL DEFAULT 'kg'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pesanan (
  id_pesanan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_pesanan VARCHAR(30) NOT NULL UNIQUE,
  id_pelanggan INT UNSIGNED NOT NULL,
  id_layanan INT UNSIGNED NOT NULL,
  id_pengelola INT UNSIGNED NULL,
  jumlah DECIMAL(10,2) NOT NULL DEFAULT 0,
  total_harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  tanggal_masuk DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estimasi_selesai DATETIME NULL,
  status ENUM('Diterima','Diproses','Dikemas','Selesai') NOT NULL DEFAULT 'Diterima',
  catatan TEXT NULL,
  CONSTRAINT fk_pesanan_pelanggan FOREIGN KEY (id_pelanggan)
    REFERENCES pelanggan(id_pelanggan),
  CONSTRAINT fk_pesanan_layanan FOREIGN KEY (id_layanan)
    REFERENCES layanan(id_layanan),
  CONSTRAINT fk_pesanan_pengelola FOREIGN KEY (id_pengelola)
    REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS riwayat_status (
  id_riwayat INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT UNSIGNED NOT NULL,
  id_pengelola INT UNSIGNED NULL,
  status_baru ENUM('Diterima','Diproses','Dikemas','Selesai') NOT NULL,
  berubah_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_riwayat_pesanan FOREIGN KEY (id_pesanan)
    REFERENCES pesanan(id_pesanan),
  CONSTRAINT fk_riwayat_pengelola FOREIGN KEY (id_pengelola)
    REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
) ENGINE=InnoDB;


-- --------------------- FITUR IN-SCOPE TAMBAHAN ---------------------
-- Pemberitahuan selesai: catatan pesan yang disiapkan/disalin/dikirim MANUAL.
-- Bukan integrasi WhatsApp otomatis.
CREATE TABLE IF NOT EXISTS notifikasi (
  id_notifikasi INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT UNSIGNED NOT NULL,
  id_pengelola INT UNSIGNED NULL,
  kanal ENUM('WhatsApp') NOT NULL DEFAULT 'WhatsApp',
  pesan TEXT NOT NULL,
  status_pengiriman ENUM('Disiapkan','Disalin','Dikirim Manual') NOT NULL DEFAULT 'Disiapkan',
  dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dikirim_pada DATETIME NULL,
  CONSTRAINT fk_notifikasi_pesanan FOREIGN KEY (id_pesanan)
    REFERENCES pesanan(id_pesanan),
  CONSTRAINT fk_notifikasi_pengelola FOREIGN KEY (id_pengelola)
    REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Pencatatan sementara: data yang DIMASUKKAN KEMBALI setelah sistem pulih.
-- Tidak berarti database bisa menerima input ketika MySQL mati.
CREATE TABLE IF NOT EXISTS catatan_sementara (
  id_catatan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_sementara VARCHAR(30) NOT NULL UNIQUE,
  nama_pelanggan VARCHAR(100) NOT NULL,
  no_whatsapp VARCHAR(20) NOT NULL,
  rincian_layanan TEXT NOT NULL,
  catatan TEXT NULL,
  id_pengelola INT UNSIGNED NULL,
  status_sinkronisasi ENUM('Belum','Sudah') NOT NULL DEFAULT 'Belum',
  id_pesanan INT UNSIGNED NULL UNIQUE,
  dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  disinkronkan_pada DATETIME NULL,
  CONSTRAINT fk_catatan_pengelola FOREIGN KEY (id_pengelola)
    REFERENCES pengelola(id_pengelola) ON DELETE SET NULL,
  CONSTRAINT fk_catatan_pesanan FOREIGN KEY (id_pesanan)
    REFERENCES pesanan(id_pesanan) ON DELETE SET NULL
) ENGINE=InnoDB;
