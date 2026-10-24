# Peta Halaman Website dWarna Laundry — Versi V3

Satu website, dua area akses dan satu database `dwarna_laundry` (7 tabel).

| Halaman | File | Akses |
|---|---|---|
| Beranda | `index.php` | Publik |
| Layanan | Bagian dari `index.php` | Publik |
| Cek Status | `pages/cek-status.php` | Publik tanpa login; perlu kode & WhatsApp |
| Cara Order | `pages/cara-order.php` | Publik |
| Kontak | `pages/kontak.php` | Publik |
| Login Pengelola | `pages/admin/login.php` | Form login |
| Dashboard | `pages/admin/dashboard.php` | Sesi pengelola |
| Pesanan | `pages/admin/pesanan.php` | Sesi pengelola |
| Detail/ubah status | `pages/admin/detail-pesanan.php?id=...` | Sesi pengelola |
| Tambah Pesanan | `pages/admin/tambah-pesanan.php` | Sesi pengelola |
| Pelanggan | `pages/admin/pelanggan.php` | Sesi pengelola |
| Tambah Pelanggan | `pages/admin/tambah-pelanggan.php` | Sesi pengelola |
| Riwayat Status | `pages/admin/riwayat.php` | Sesi pengelola |
| Pemberitahuan Selesai | `pages/admin/notifikasi.php` | Sesi pengelola, pengiriman manual |
| Catatan Sementara | `pages/admin/catatan-sementara.php` | Sesi pengelola |

Warna: `#D90B20`, `#96151E`, `#FDEBED`, `#FFFFFF`, `#F9FAFB`, `#1F2937`. Tipografi: Inter.

Status: **Diterima → Diproses → Dikemas → Selesai**.

**Tidak ada sistem pembayaran, fitur keuangan, atau riwayat pembayaran.** Harga layanan hanya informasi pada rincian pesanan.

Login memakai password hash, session, CSRF, dan halaman admin terproteksi. Buat akun pertama kali melalui `setup-admin.php` di localhost dan hapus skrip setelah berhasil.
