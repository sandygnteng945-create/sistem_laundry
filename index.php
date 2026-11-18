
<?php
include 'koneksi.php';

$total_pelanggan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggan")
)['total'];

$total_transaksi = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi")
)['total'];

$total_pendapatan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM transaksi")
)['total'] ?? 0;

$total_proses = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE status='Proses'")
)['total'];

$bulan = [
    1 => 'Januari', 'Februari', 'Maret', 'April',
    'Mei', 'Juni', 'Juli', 'Agustus',
    'September', 'Oktober', 'November', 'Desember'
];

$tanggal = date('d') . ' ' . $bulan[(int)date('n')] . ' ' . date('Y');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Laundry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">

    <div class="brand">
        <div class="brand-icon">🧺</div>
        <h2>Laundry</h2>
        <p>Sistem Informasi</p>
    </div>

    <nav class="navigation">
        <a href="index.php" class="nav-link active">
            <span>⌂</span> Dashboard
        </a>

        <a href="pelanggan.php" class="nav-link">
            <span>👥</span> Pelanggan
        </a>

        <a href="layanan.php" class="nav-link">
            <span>👕</span> Layanan
        </a>

        <a href="transaksi.php" class="nav-link">
            <span>▤</span> Transaksi
        </a>

        <a href="status.php" class="nav-link">
            <span>🫧</span> Status Cucian
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="bubble"></div>
        <div class="washing-icon">🧺</div>
        <p>Laundry Lebih Mudah!</p>
    </div>

</div>

<main class="main-content">

    <div class="topbar">
        <div>
            <h3>Dashboard</h3>
            <p>Selamat datang di halaman pengelolaan laundry.</p>
        </div>

        <div class="topbar-right">
            <span class="date">📅 <?= $tanggal ?></span>
            <div class="admin-icon">👤</div>
        </div>
    </div>

    <section class="welcome-banner">
        <div class="banner-content">
            <span class="banner-label">LAUNDRY MANAGEMENT SYSTEM</span>
            <h1>Selamat Datang! 👋</h1>
            <p>Selamat menggunakan aplikasi laundry sederhana.</p>
            <div class="banner-line"></div>
        </div>

        <div class="banner-image">
            🧺
            <span>🫧</span>
            <span>✨</span>
        </div>
    </section>

    <section class="section-heading">
        <div>
            <h2>Ringkasan Dashboard</h2>
            <p>Informasi data laundry saat ini</p>
        </div>
    </section>

    <section class="stats-container">

        <div class="stat-card blue">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Pelanggan</h3>
                <h1><?= $total_pelanggan ?></h1>
                <p>Data pelanggan</p>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">🧺</div>
            <div class="stat-info">
                <h3>Total Transaksi</h3>
                <h1><?= $total_transaksi ?></h1>
                <p>Transaksi laundry</p>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Total Pendapatan</h3>
                <h1>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h1>
                <p>Pendapatan laundry</p>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">✓</div>
            <div class="stat-info">
                <h3>Cucian Diproses</h3>
                <h1><?= $total_proses ?></h1>
                <p>Sedang dikerjakan</p>
            </div>
        </div>

    </section>

    <footer class="footer">
        <span>💙 © 2026 Sistem Informasi Laundry</span>
        <span>Laundry Lebih Mudah, Hidup Lebih Praktis.</span>
    </footer>

</main>

</body>
</html>
