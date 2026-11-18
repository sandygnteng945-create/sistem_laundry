
<?php
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    if ($nama == '' || $no_hp == '') {
        $error = "Nama dan nomor HP wajib diisi!";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO pelanggan (nama,no_hp,alamat) VALUES (?,?,?)"
        );

        mysqli_stmt_bind_param($stmt, "sss", $nama, $no_hp, $alamat);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: pelanggan.php");
            exit;
        } else {
            $error = "Gagal menambahkan pelanggan!";
        }
    }
}

$data = mysqli_query(
    $conn,
    "SELECT * FROM pelanggan ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pelanggan - Laundry</title>
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
        <a href="index.php" class="nav-link">
            <span>⌂</span> Dashboard
        </a>

        <a href="pelanggan.php" class="nav-link active">
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
        <div class="washing-icon">🧺</div>
        <p>Laundry Lebih Mudah!</p>
    </div>

</div>

<main class="main-content">

    <div class="topbar">
        <div>
            <h3>Data Pelanggan</h3>
            <p>Kelola informasi pelanggan laundry kamu.</p>
        </div>

        <div class="topbar-right">
            <span class="date">📅 <?= date('d-m-Y') ?></span>
            <div class="admin-icon">👤</div>
        </div>
    </div>

    <section class="page-banner">
        <div>
            <h1>Data Pelanggan 👥</h1>
            <p>Tambahkan dan kelola data pelanggan laundry dengan mudah.</p>
        </div>
        <div class="page-banner-icon">🧺</div>
    </section>

    <section class="customer-form card">
        <div class="card-heading">
            <div class="heading-icon">＋</div>
            <div>
                <h2>Tambah Pelanggan</h2>
                <p>Masukkan informasi pelanggan baru.</p>
            </div>
        </div>

        <?php if ($error != ''): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama"
                           placeholder="Masukkan nama pelanggan" required>
                </div>

                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp"
                           placeholder="Contoh: 081234567890" required>
                </div>

                <div class="form-group full-width">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              placeholder="Masukkan alamat pelanggan"
                              rows="3"></textarea>
                </div>

            </div>

            <button type="submit" class="save-button">
                ＋ Simpan Pelanggan
            </button>

        </form>
    </section>

    <section class="customer-table card">

        <div class="card-heading">
            <div class="heading-icon">▤</div>
            <div>
                <h2>Daftar Pelanggan</h2>
                <p>Data pelanggan yang telah terdaftar.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($data) > 0):
                    ?>

                    <?php while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="customer-name">
                            <?= htmlspecialchars($row['nama']) ?>
                        </td>
                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                        <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-data">
                            🧺 Belum ada data pelanggan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>

    <footer class="footer">
        <span>💙 © 2026 Sistem Informasi Laundry</span>
        <span>Laundry Lebih Mudah, Hidup Lebih Praktis.</span>
    </footer>

</main>

</body>
</html>
