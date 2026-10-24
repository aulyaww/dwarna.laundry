# dWarna Laundry Palopo — Website Publik & Pengelola

Proyek web untuk **dWarna Laundry Palopo**. Dibangun sebagai bagian dari Tugas 6: *Project Directory Architecture & Environment Setup*, dengan pengembangan login dan pengelolaan data berbasis MySQL.

## Cakupan (In-Scope)

- **Publik / tanpa login**: beranda, informasi layanan, cara order, kontak, serta **cek status** menggunakan kode pesanan + nomor WhatsApp yang sesuai.
- **Admin bersama / wajib login**: dashboard, data pelanggan, tambah pelanggan, daftar pesanan, tambah pesanan, detail pesanan, ubah status (Diterima → Diproses → Dikemas → Selesai), dan riwayat status.
- **Operasional pendukung**: penyusunan pesan WhatsApp selesai untuk dikirim manual dan pencatatan kembali catatan offline setelah gangguan.
- **Tidak ada fitur pembayaran**, pembayaran online, laporan keuangan, stok, absensi, multi-cabang, atau sistem loyalitas.

## Tech Stack

PHP native (PDO + session), HTML, CSS responsif, JavaScript vanilla, MySQL/MariaDB, XAMPP/Apache, VS Code, Git. Tampilan merah-putih menggunakan pendekatan high-fidelity Tugas 5; beberapa ilustrasi bukan aset foto asli mitra.

## Cara instal di localhost (XAMPP)

1. **Backup database lama dahulu** melalui phpMyAdmin > Export > SQL. Jangan menimpa database aktif yang telah berisi data.
2. Salin folder `dwarna-laundry` ke `C:\xampp\htdocs\dwarna-laundry` (pastikan `index.php` ada langsung di dalam folder ini).
3. Jalankan **Apache** dan **MySQL** melalui XAMPP.
4. Jika database **belum ada**, di phpMyAdmin import `database/schema.sql`. Opsional: import `database/seed.sql` hanya ke database baru yang kosong; berisi data fiktif.
5. Jika database **sudah ada dan berisi 7 tabel**, **jangan mengimpor ulang** `schema.sql` atau `seed.sql`. Sistem ini menggunakan tabel: `pengelola`, `pelanggan`, `layanan`, `pesanan`, `riwayat_status`, `notifikasi`, `catatan_sementara`.
6. Koneksi default berada di `config/database.php`: host `127.0.0.1`, database `dwarna_laundry`, user `root`, password kosong (default XAMPP). Bila berbeda, salin `config/database.local.example.php` menjadi `config/database.local.php` dan isi konfigurasi yang sesuai. File `.local.php` tidak boleh diunggah ke Git.
7. Buka website publik: `http://localhost/dwarna-laundry/`.
8. **Akun Admin bersama**: untuk basis data lama dengan akun pemilik, import sekali `database/migrasi_admin_tunggal.sql` (setelah backup), lalu login memakai username/password lama. Buka menu **Akun Admin** untuk menggantinya memakai username/password baru, tanpa menghapus relasi pesanan.
9. Buka `http://localhost/dwarna-laundry/pages/admin/login.php` untuk login akun Admin. Jika berhasil, pengguna akan diarahkan ke dashboard. Klik **Keluar Akun** di sidebar untuk logout.
10. **Jangan aktifkan lagi `setup-admin.php` pada database yang telah memiliki akun.** Jika file masih ada, hapus dari folder website. Perubahan akun dilakukan lewat menu **Akun Admin** setelah login.

## Urutan mencoba fitur

1. Login sebagai Admin.
2. Buka **Pelanggan → Tambah Pelanggan**.
3. Buka **Pesanan → Tambah Pesanan**, pilih pelanggan dan layanan. Sistem membuat kode unik dan menyimpan pesanan ke MySQL.
4. Buka **Pesanan → Detail**, ubah status pesanan; perubahan tersimpan di `riwayat_status`.
5. Setelah status menjadi Selesai, sebuah pesan muncul di menu **Pemberitahuan** untuk **disalin dan dikirim manual**. Tidak ada integrasi WhatsApp otomatis.
6. Buka `pages/cek-status.php` dari halaman publik dan masukkan **kode pesanan + nomor WhatsApp tepat sesuai data pelanggan** untuk melihat status terbaru tanpa login.

## Struktur Folder

```text
dwarna-laundry/
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/{logo,products,icons,mitra}/
├── config/database.php
├── database/{schema.sql,seed.sql}
├── includes/{app.php,header.php,navbar.php,footer.php}
├── pages/
│   ├── cara-order.php
│   ├── cek-status.php
│   ├── kontak.php
│   └── admin/{login,logout,dashboard,pesanan,pelanggan,...}.php
├── docs/
├── index.php
├── .gitignore
└── README.md
```

## Catatan Keamanan & Batasan

- Password disimpan memakai `password_hash()` dan diverifikasi dengan `password_verify()`, bukan password teks biasa. Setiap perubahan data memakai prepared statements dan CSRF token. Halaman admin mensyaratkan sesi login; logout melalui POST.
- Form login membatasi percobaan gagal per sesi (5 kali, 15 menit). Ini **belum** perlindungan menyeluruh untuk deployment internet terbuka; untuk produksi tambahkan rate limit IP, HTTPS, backup, audit, dan pengaturan keamanan server.
- Jangan pernah unggah `config/database.local.php`, database dump yang berisi data pelanggan asli, atau file `setup-admin.php` ke GitHub publik.
- Dalam mode lokal ini, daftar pelanggan/pesanan yang diimport dari `seed.sql` bersifat fiktif. Perlu uji pengguna, review mitra, dan pemeriksaan keamanan sebelum penggunaan operasional sesungguhnya.
- **Catatan sementara** menyimpan transkripsi catatan manual setelah server kembali online; fungsi menyinkronkan catatan menjadi pesanan otomatis belum tersedia.
- Jika halaman publik terlihat tetapi login gagal, periksa modul PHP **pdo_mysql**, koneksi XAMPP, tujuh tabel final, dan kecocokan username/password.

## Bukti untuk Laporan Tugas 6

Screenshot VS Code tree folder, tabel phpMyAdmin & relational schema, localhost, halaman login & dashboard, dan informasi repo/Drive. **GitHub dan Google Drive perlu diunggah pengguna secara terpisah**, link belum dibuat dari paket ZIP ini.

## Akun Admin Tunggal (V3.2)

Pemilik dan karyawan memakai satu akun Admin bersama: form login hanya **username** dan **password**. Tidak ada kolom nama pemilik saat mengganti akun. Untuk data lama, jalankan `database/migrasi_admin_tunggal.sql` **sekali** setelah backup, kemudian login dengan kredensial lama dan gunakan menu **Akun Admin** untuk menggantinya. Nama di database disimpan sebagai teks tetap `Admin` hanya demi kompatibilitas relasi. Penggunaan satu akun membuat tindakan per orang tidak dapat diaudit; batasi siapa yang mengetahui password dan ganti ketika karyawan berganti.


## V6 — Desain Premium Interaktif

- Tetap memakai **satu** file `assets/css/style.css` dan **satu** file `assets/js/main.js`.
- Beranda diperbarui: foto hero slideshow, kategori layanan dengan filter JavaScript, FAQ, animasi scroll, proses dan CTA cek status.
- Dashboard Admin: tampilan baru dan visualisasi jumlah pesanan **berdasarkan nilai sebenarnya di database**.
- Konten dan foto mitra: ganti file JPG di `assets/images/mitra/` menggunakan nama lama (`hero.jpg`, `reguler.jpg`, `express.jpg`, `setrika.jpg`, `bed-cover.jpg`, `login.jpg`, `status.jpg`, `detail.jpg`). Foto bawaan ZIP adalah **placeholder**, bukan dokumentasi mitra.
- Logo: ganti `assets/images/logo/logo-mitra.jpg`.
- Tidak ada checkout/pembayaran, tidak ada login pelanggan, tidak ada pesanan online baru.
- Instal: backup dulu website + database, timpa hanya file PHP/CSS/JS di `PATCH_V6` atau gunakan folder proyek V6 untuk instal baru dengan database lama tetap disimpan.
- Keamanan: hapus `setup-admin.php` setelah akun dibuat; file itu tidak disertakan dalam paket V6.
- Uji di localhost: halaman beranda, filter kartu, slideshow, menu mobile, FAQ; lalu login Admin, ubah status pesanan percobaan dan cek status dari halaman pelanggan.
