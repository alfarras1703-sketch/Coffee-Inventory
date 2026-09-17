<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

// Proses simpan kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Validasi
    if ($name === '') {
        die("Nama kategori wajib diisi.");
    }

    // Simpan kategori
    $queryInsert = mysqli_query($conn, "
        INSERT INTO categories (name, description)
        VALUES ('$name', '$description')
    ");

    if ($queryInsert) {
        header("Location: kategori.php");
        exit;
    } else {
        die("Gagal menyimpan kategori: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori - Coffee Inventory</title>

    <link rel="stylesheet" href="assets/css/style.css?v=2">

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                <img
                    src="assets/icon/Logo coffee inventoy.png"
                    alt="Coffee Inventory"
                >
            </div>

            <div>
                <h2>Coffee</h2>
                <span>Inventory</span>
            </div>

        </div>


        <nav class="menu">

            <a
                href="index.php"
                class="menu-item"
            >
                <span class="menu-icon">
                    <img
                        src="assets/icon/Logo dashboard.png"
                        alt="Dashboard"
                    >
                </span>
                Dashboard
            </a>


            <a
                href="produk.php"
                class="menu-item"
            >
                <span class="menu-icon">
                    <img
                        src="assets/icon/Logo produk.png"
                        alt="Produk"
                    >
                </span>
                Produk
            </a>


            <a
                href="stok.php"
                class="menu-item"
            >
                <span class="menu-icon">
                    <img
                        src="assets/icon/Logo stok.png"
                        alt="Stok"
                    >
                </span>
                Stok
            </a>


            <a
                href="kategori.php"
                class="menu-item active"
            >
                <span class="menu-icon">
                    <img
                        src="assets/icon/Logo kategori.png"
                        alt="Kategori"
                    >
                </span>
                Kategori
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a
                href="logout.php"
                class="menu-item logout"
            >
                <span class="menu-icon">
                    <img src="assets/icon/Logo lgout.png" alt="Logout">
                </span>
                Logout
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">


        <!-- HEADER -->
        <header class="topbar">

            <div>

                <h1>
                    Tambah Kategori
                </h1>

                <p>
                    Tambahkan kategori baru ke dalam inventory.
                </p>

            </div>


            <div class="admin-profile">

                <div class="avatar">
                    A
                </div>

                <div>

                    <strong>
                        Administrator
                    </strong>

                    <small>
                        Admin
                    </small>

                </div>

            </div>

        </header>


        <!-- FORM -->
        <section class="content-card">

            <div class="card-header">

                <div>

                    <h2>
                        Form Kategori
                    </h2>

                    <p>
                        Isi data kategori dengan lengkap.
                    </p>

                </div>

            </div>


            <form method="POST" class="product-form">

                <div class="form-group full">

                    <label>
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Contoh: Dessert"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label>
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Deskripsi kategori"
                    ></textarea>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Simpan Kategori
                    </button>


                    <a
                        href="kategori.php"
                        class="btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>