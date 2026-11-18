
<?php
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama_layanan']);
    $harga = (int) $_POST['harga'];

    if ($nama == '' || $harga < 1) {
        $error = "Nama layanan dan harga harus valid!";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO layanan (nama_layanan, harga) VALUES (?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "si", $nama, $harga);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: layanan.php");
            exit;
        } else {
            $error = "Gagal menambahkan layanan!";
        }
    }
}

$data = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Laundry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <h2>Wash<span>Clean.</span></h2>
        <p>Management System</p>
    </div>

    <div class="sidebar-menu">
        <p class="menu-title">MENU UTAMA</p>

        <a href="index.php">
            <span>▦</span> Dashboard
        </a>

        <a href="pelanggan.php">
            <span>♙</span> Pelanggan
        </a>

        <a href="layanan.php" class="active">
            <span>✧</span> Layanan
        </a>

        <a href="transaksi.php">
            <span>▤</span> Transaksi
        </a>

        <a href="status.php">
            <span>◷</span> Status Cucian
        </a>
    </div>

    <div class="sidebar-bottom">
        <p>Sistem Informasi Laundry</p>
    </div>
</div>

<div class="main-content">

    <div class="topbar">
        <div>
            <h2>Data Layanan</h2>
            <p>Kelola jenis layanan laundry kamu</p>
        </div>
        <div class="admin-info">
            <span>Admin Laundry</span>
            <div class="admin-avatar">A</div>
        </div>
    </div>

    <div class="page-banner">
        <div>
            <h2>Kelola Layanan Laundry</h2>
            <p>Tambahkan dan lihat daftar layanan laundry yang tersedia.</p>
        </div>
        <div class="banner-icon">🧺</div>
    </div>

    <?php if ($error != ''): ?>
        <div class="alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="customer-form card">
        <div class="card-heading">
            <div class="heading-icon">✚</div>
            <div>
                <h3>Tambah Layanan</h3>
                <p>Masukkan nama layanan dan harga per kilogram.</p>
            </div>
        </div>

        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input
                        type="text"
                        name="nama_layanan"
                        placeholder="Contoh: Cuci Kering"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Harga Per Kilogram (Rp)</label>
                    <input
                        type="number"
                        name="harga"
                        min="1"
                        placeholder="Contoh: 7000"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="save-button">
                + Simpan Layanan
            </button>
        </form>
    </div>

    <div class="customer-table card">
        <div class="card-heading">
            <div class="heading-icon">▤</div>
            <div>
                <h3>Daftar Layanan</h3>
                <p>Daftar harga layanan laundry yang tersedia.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Harga / Kg</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td>
                            <strong>
                                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                            </strong>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">
                            Belum ada layanan yang ditambahkan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        © 2026 WashClean Laundry Management System
    </div>

</div>

</body>
</html>
