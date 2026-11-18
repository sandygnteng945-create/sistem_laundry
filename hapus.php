<?php
include 'koneksi.php';

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM transaksi WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: transaksi.php?pesan=hapus");
        exit;
    } else {
        echo "Gagal menghapus transaksi!";
    }

} else {
    header("Location: transaksi.php");
    exit;
}
?>