<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

// Total produk
$queryProducts = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProducts = mysqli_fetch_assoc($queryProducts)['total'];

// Total kategori
$queryCategories = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
$totalCategories = mysqli_fetch_assoc($queryCategories)['total'];

// Total stok
$queryStock = mysqli_query($conn, "SELECT SUM(stock) AS total FROM products");
$totalStock = mysqli_fetch_assoc($queryStock)['total'] ?? 0;

// Produk stok menipis
$queryLowStock = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM products WHERE stock <= 5"
);
$lowStock = mysqli_fetch_assoc($queryLowStock)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coffee Inventory</title>

    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">
                <img src="assets/icon/Logo coffee inventoy.png" alt="Coffee Inventory">
            </div>
            
            <div class="brand-text">
                <h2>Coffee</h2>
                <span>Inventory</span>
            </div>
        </div>

        <nav class="menu">

            <a href="index.php" class="menu-item active">
            <span class="menu-icon">
                <img src="assets/icon/Logo dashboard.png" alt="Dashboard">
            </span>
            Dashboard
            </a>

            <a href="produk.php" class="menu-item">
            <span class="menu-icon">
                <img src="assets/icon/Logo produk.png" alt="Produk">
            </span>
            Produk
            </a>

            <a href="stok.php" class="menu-item">
            <span class="menu-icon">
                <img src="assets/icon/Logo stok.png" alt="Stok">
            </span>
            Stok
            </a>

            <a href="kategori.php" class="menu-item">
            <span class="menu-icon">
                <img src="assets/icon/Logo kategori.png" alt="Kategori">
            </span>
            Kategori
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="logout.php" class="menu-item logout">
                <span class="menu-icon">
                    <img src="assets/icon/Logo lgout.png" alt="Logout">
                </span>
                <span class="menu-text">Logout</span>
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- HEADER -->
        <header class="topbar">

            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang di Coffee Inventory</p>
            </div>

            <div class="admin-profile">

                <div class="avatar">A</div>

                <div>
                    <strong>Administrator</strong>
                    <small>Admin</small>
                </div>

            </div>

        </header>


        <!-- STATISTICS -->
        <section class="stats">

            <div class="stat-card">

                <div class="stat-icon">
                    <img src="assets/icon/Logo coffee inventoy.png" alt="Produk">
                </div>

                <div>
                    <p>Total Produk</p>
                    <h2><?= $totalProducts; ?></h2>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    <img src="assets/icon/Logo stok.png" alt="Stok">
                </div>

                <div>
                    <p>Total Stok</p>
                    <h2><?= $totalStock; ?></h2>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    <img src="assets/icon/Logo kategori.png" alt="Kategori">
                </div>

                <div>
                    <p>Kategori</p>
                    <h2><?= $totalCategories; ?></h2>
                </div>

            </div>


            <div class="stat-card warning">

            <div class="stat-icon">
                <img src="assets/icon/Logo Stok Menipis.png" alt="Stok Menipis">
            </div>

                <div>
                    <p>Stok Menipis</p>
                    <h2><?= $lowStock; ?></h2>
                </div>

            </div>

        </section>


        <!-- CONTENT -->
        <section class="content-card">

            <div class="card-header">

                <div>
                    <h2>Ringkasan Inventory</h2>
                    <p>Informasi singkat mengenai persediaan coffee shop.</p>
                </div>

                <a href="tambah-produk.php" class="btn-primary">
                    + Tambah Produk
                </a>

            </div>

            <div class="empty-state">

            <div class="empty-icon">
                <img src="assets/icon/Logo coffee inventoy.png" alt="Coffee Inventory">
            </div>

                <h3>Inventory Coffee Shop</h3>

                <p>
                    Kelola produk, stok, dan kategori melalui menu di sebelah kiri.
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>