<?php
$pageTitle=$pageTitle ?? 'Beranda';
$rootPrefix=$rootPrefix ?? '';
$isAdmin=$isAdmin ?? false;
$isLogin=$isLogin ?? false;
?><!doctype html>
<html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="dWarna Laundry Palopo, laundry praktis dengan informasi layanan dan pelacakan status pesanan.">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | dWarna Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $rootPrefix ?>assets/css/style.css?v=99">
</head><body class="<?= $isLogin ? 'login-body' : ($isAdmin ? 'admin-body' : 'public-body') ?>">
<?php require __DIR__.'/navbar.php'; ?>
<?php if($isAdmin && !$isLogin): ?>
<div class="admin-main"><div class="admin-topbar"><button class="sidebar-toggle" type="button" aria-label="Buka navigasi" data-sidebar-toggle>☰</button><span class="top-search" aria-label="Sistem pengelola">◈ &nbsp; Sistem Pengelolaan Laundry</span>
<span class="top-user">
    <i class="fa-solid fa-circle-user"></i>
    &nbsp; Admin
</span>
</div><div class="admin-content">
<?php elseif($isLogin): ?><main class="login-main">
<?php else: ?><main>
<?php endif; ?>
