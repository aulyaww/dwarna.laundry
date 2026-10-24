<?php
/** Fasilitas aplikasi dWarna Laundry. Menggunakan database schema final 7 tabel. */
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('DWARNA_SESSION');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
require_once __DIR__ . '/../config/database.php';

function db(): PDO {
    static $connection = null;
    if ($connection === null) $connection = koneksiDatabase();
    return $connection;
}
function h(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function rupiah($value): string { return 'Rp ' . number_format((float)$value, 0, ',', '.'); }
function waktu(?string $value): string { return $value ? date('d/m/Y H:i', strtotime($value)) : '–'; }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_input(): string { return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">'; }
function validate_csrf(): void {
    if (!hash_equals(csrf_token(), (string)($_POST['csrf_token'] ?? ''))) {
        http_response_code(419); exit('Sesi formulir berakhir. Kembali ke halaman sebelumnya, muat ulang, lalu coba lagi.');
    }
}
function redirect_to(string $path): void { header('Location: ' . $path, true, 303); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function show_flash(): string {
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    return $f ? '<div role="status" class="app-alert ' . ($f['type']==='error'?'alert-error':'alert-success') . '">' . h($f['message']) . '</div>' : '';
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_admin(): void {
    if (empty($_SESSION['user']['id'])) redirect_to('login.php');
}
function status_class(string $status): string {
    return match ($status) {'Selesai'=>'green','Dikemas'=>'blue','Diproses'=>'amber',default=>'red'};
}
function status_badge(string $status): string {
    return '<span class="status-chip ' . status_class($status) . '"><i class="dot"></i> ' . h($status) . '</span>';
}
function query_order(int $id): ?array {
    $q = db()->prepare('SELECT o.*, p.nama AS pelanggan_nama, p.no_whatsapp, p.alamat, l.nama_layanan, l.satuan, l.harga_satuan, a.nama AS pencatat FROM pesanan o JOIN pelanggan p ON p.id_pelanggan=o.id_pelanggan JOIN layanan l ON l.id_layanan=o.id_layanan LEFT JOIN pengelola a ON a.id_pengelola=o.id_pengelola WHERE o.id_pesanan=?');
    $q->execute([$id]); return $q->fetch() ?: null;
}
function admin_page_head(string $title, string $desc=''): void {
    global $pageTitle,$rootPrefix,$isAdmin;
    $pageTitle=$title; $rootPrefix='../../'; $isAdmin=true;
    require __DIR__.'/header.php';
    echo show_flash();
    echo '<div class="admin-page-title"><div><h1>'.h($title).'</h1><p>'.h($desc).'</p></div></div>';
}
