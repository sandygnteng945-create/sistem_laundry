<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_laundry"
);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>