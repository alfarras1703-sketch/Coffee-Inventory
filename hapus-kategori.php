<?php
require_once "config/database.php";

// Ambil ID kategori
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Cek ID
if ($id <= 0) {
    die("ID kategori tidak valid.");
}

// Hapus kategori
$queryDelete = mysqli_query($conn, "
    DELETE FROM categories
    WHERE id = $id
");

if ($queryDelete) {
    header("Location: kategori.php");
    exit;
} else {
    die("Gagal menghapus kategori: " . mysqli_error($conn));
}
?>