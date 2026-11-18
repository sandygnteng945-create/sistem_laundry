
<?php
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pelanggan_id = (int)$_POST['pelanggan_id'];
    $layanan_id = (int)$_POST['layanan_id'];
    $berat = (float)$_POST['berat'];
    $tanggal = $_POST['tanggal'];

    if ($berat <= 0 || !$tanggal || $pelanggan_id < 1 || $layanan_id < 1) {
        $error = "Data transaksi harus diisi dengan benar!";
    } else {

        $stmt = mysqli_prepare($conn, "SELECT harga FROM layanan WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $layanan_id);
        mysqli_stmt_execute($stmt);
        $layananTerpilih = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        $stmt = mysqli_prepare($conn, "SELECT id FROM pelanggan WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $pelanggan_id);
        mysqli_stmt_execute($stmt);
        $pelangganTerpilih = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$layananTerpilih || !$pelangganTerpilih) {
            $error = "Pelanggan atau layanan tidak ditemukan!";
        } else {

            $total = (int)round($berat * $layananTerpilih['harga']);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO transaksi
                (pelanggan_id, layanan_id, berat, total_harga, tanggal)
                VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iidds",
                $pelanggan_id,
                $layanan_id,
                $berat,
                $total,
                $tanggal
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: transaksi.php");
                exit;
            } else {
                $error = "Gagal menyimpan transaksi!";
            }
        }
    }
}

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama");
$layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan");

$data = mysqli_query(
    $conn,
    "SELECT transaksi.*, pelanggan.nama, layanan.nama_layanan
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
    <title>Transaksi Laundry</title>
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

        <a href="transaksi.php" class="active">
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
            <h2>Data Transaksi</h2>
            <p>Kelola transaksi laundry pelanggan</p>
        </div>

        <div class="admin-info">
            <span>Admin Laundry</span>
            <div class="admin-avatar">A</div>
        </div>
    </div>

    <div class="page-banner">
        <div>
            <h2>Transaksi Laundry</h2>
            <p>Catat pesanan cucian dan lihat daftar transaksi pelanggan.</p>
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
                <h3>Tambah Transaksi</h3>
                <p>Masukkan informasi pesanan cucian pelanggan.</p>
            </div>
        </div>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <select name="pelanggan_id" required>
                        <option value="">-- Pilih Pelanggan --</option>

                        <?php while ($p = mysqli_fetch_assoc($pelanggan)): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Layanan</label>
                    <select name="layanan_id" required>
                        <option value="">-- Pilih Layanan --</option>

                        <?php while ($l = mysqli_fetch_assoc($layanan)): ?>
                            <option value="<?= $l['id'] ?>">
                                <?= htmlspecialchars($l['nama_layanan']) ?>
                                - Rp <?= number_format($l['harga'], 0, ',', '.') ?>/Kg
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Berat Cucian (Kg)</label>
                    <input
                        type="number"
                        name="berat"
                        min="0.1"
                        step="0.1"
                        placeholder="Contoh: 3.5"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Tanggal Transaksi</label>
                    <input
                        type="date"
                        name="tanggal"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

            </div>

            <button type="submit" class="save-button">
                + Simpan Transaksi
            </button>

        </form>
    </div>

    <div class="customer-table card">

        <div class="card-heading">
            <div class="heading-icon">▤</div>
            <div>
                <h3>Daftar Transaksi</h3>
                <p>Informasi seluruh transaksi laundry.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td><?= number_format($row['berat'], 1, ',', '.') ?> Kg</td>
                        <td>
                            <strong>
                                Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                            </strong>
                        </td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada transaksi.
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
