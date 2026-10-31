
# Tugas 7 - Core Feature Implementation
## dWarna Laundry Palopo

### 1. Modularisasi Komponen UI
Website menggunakan komponen PHP terpisah:
- includes/header.php
- includes/navbar.php
- includes/footer.php

### 2. Dynamic Data Binding
Data pelanggan ditampilkan menggunakan query MySQL
melalui PHP.

Pengujian:
- Data awal: 5 pelanggan
- Data percobaan ditambahkan melalui phpMyAdmin
- Setelah refresh: 6 pelanggan
- Hasil: Berhasil

### 3. Core CRUD / Form
Fitur yang diuji: Edit Pelanggan.

Alur:
1. Admin membuka formulir Edit Pelanggan.
2. Admin mengubah nama dan alamat data percobaan.
3. Perubahan disimpan melalui website.
4. Data terbaru diverifikasi di phpMyAdmin.

Hasil: Berhasil.

### 4. Repository
https://github.com/aulyaww/dwarna.laundry
