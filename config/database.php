<?php
/**
 * Koneksi PDO MySQL untuk lingkungan lokal. TIDAK otomatis dipanggil
 * oleh halaman agar struktur awal tetap bisa dilihat sebelum impor SQL.
 * Buat config/database.local.php jika kredensial lokal berbeda.
 */
function koneksiDatabase(): PDO
{
    $pengaturan = [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'nama' => getenv('DB_NAME') ?: 'dwarna_laundry',
        'user' => getenv('DB_USER') ?: 'root',
        'sandi' => getenv('DB_PASS') ?: '',
    ];
    $lokal = __DIR__ . '/database.local.php';
    if (is_file($lokal)) {
        $pengaturan = array_merge($pengaturan, require $lokal);
    }
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $pengaturan['host'], $pengaturan['port'], $pengaturan['nama']);
    return new PDO($dsn, $pengaturan['user'], $pengaturan['sandi'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
