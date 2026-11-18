
<?php
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $daftar_status = ['Proses', 'Selesai', 'Diambil'];

    if ($id < 1 || !in_array($status, $daftar_status, true)) {
        $error = "Data status tidak valid!";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE transaksi SET status=? WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, "si", $status, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: status.php");
            exit;
        } else {
            $error = "Gagal mengubah status!";
        }
    }
}

$data = mysqli_query(
    $conn,
    "SELECT transaksi.*, pelanggan.nama,
    layanan.nama_layanan
    FROM transaksi
    JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
    JOIN layanan ON transaksi.layanan_id = layanan.id
    ORDER BY transaksi.id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Cucian Laundry</title>
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

        <a href="layanan.php">
            <span>✧</span> Layanan
        </a>

        <a href="transaksi.php">
            <span>▤</span> Transaksi
        </a>

        <a href="status.php" class="active">
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
            <h2>Status Cucian</h2>
            <p>Pantau dan perbarui status cucian pelanggan</p>
        </div>

        <div class="admin-info">
            <span>Admin Laundry</span>
            <div class="admin-avatar">A</div>
        </div>
    </div>

    <div class="page-banner">
        <div>
            <h2>Kelola Status Cucian</h2>
            <p>Perbarui perkembangan pesanan laundry pelanggan.</p>
        </div>

        <div class="banner-icon">🧺</div>
    </div>

    <?php if ($error != ''): ?>
        <div class="alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="customer-table card">

        <div class="card-heading">
            <div class="heading-icon">◷</div>
            <div>
                <h3>Daftar Status Cucian</h3>
                <p>Informasi transaksi dan perkembangan cucian.</p>
            </div>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Layanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($row['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_layanan']) ?>
                        </td>

                        <td>
                            <strong>
                                Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                            </strong>
                        </td>

                        <td>
                            <?php if ($row['status'] == 'Proses'): ?>
                                <span class="status-label status-proses">Proses</span>
                            <?php elseif ($row['status'] == 'Selesai'): ?>
                                <span class="status-label status-selesai">Selesai</span>
                            <?php else: ?>
                                <span class="status-label status-diambil">Diambil</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <form method="POST" class="status-form">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $row['id'] ?>"
                                >

                                <select name="status" required>
                                    <option value="Proses"
                                        <?= $row['status'] == 'Proses' ? 'selected' : '' ?>>
                                        Proses
                                    </option>

                                    <option value="Selesai"
                                        <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>
                                        Selesai
                                    </option>

                                    <option value="Diambil"
                                        <?= $row['status'] == 'Diambil' ? 'selected' : '' ?>>
                                        Diambil
                                    </option>
                                </select>

                                <button type="submit" class="save-button">
                                    Update
                                </button>

                            </form>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada data cucian.
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
