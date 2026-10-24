# Catatan Revisi — dWarna Laundry V3

- Acuan visual: high-fidelity 10 layar Tugas 5, warna utama merah-putih.
- Login pengelola aktif memakai session dan username/password yang disimpan sebagai hash.
- Dashboard, pelanggan, pesanan, dan riwayat status memakai MySQL 7 tabel, bukan data tiruan dari array PHP.
- Halaman pelanggan Cek Status memverifikasi kode pesanan + nomor WhatsApp tanpa login.
- Responsif: sidebar mobile, navigasi publik mobile, form 1 kolom pada layar kecil, tabel bisa discroll horizontal.
- Tidak ada fitur pembayaran.
- Pemberitahuan selesai hanya menyiapkan pesan untuk disalin dan dikirim manual.
- Catatan sementara dimasukkan kembali setelah sistem aktif; belum sinkron otomatis.

## Perlu verifikasi sebelum dipakai mitra

Foto/logo asli, informasi usaha, kesesuaian akhir dengan Figma, hak akses detail, dan pengujian operasional. Tidak semua aset dan data usaha telah diverifikasi; gunakan data dummy untuk presentasi Tugas 6. Untuk deploy publik, tambah HTTPS, pembatasan login berbasis IP dan audit keamanan.
