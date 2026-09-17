<?php
require_once "config/database.php";

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Cek ID
if ($id <= 0) {
    die("ID produk tidak valid.");
}

// Hapus produk
$queryDelete = mysqli_query($conn, "
    DELETE FROM products
    WHERE id = $id
");

if ($queryDelete) {
    header("Location: produk.php");
    exit;
} else {
    die("Gagal menghapus produk: " . mysqli_error($conn));
}
?>