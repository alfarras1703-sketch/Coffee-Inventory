<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

/* =========================
   PROSES SIMPAN PRODUK
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_code = trim($_POST['product_code'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $unit = trim($_POST['unit'] ?? '');
    $description = trim($_POST['description'] ?? '');

    /* =========================
       VALIDASI
    ========================= */

    if (
        $product_code === '' ||
        $name === '' ||
        $category_id <= 0 ||
        $unit === ''
    ) {
        die("Data produk belum lengkap.");
    }

    if ($price < 0) {
        die("Harga produk tidak boleh negatif.");
    }

    if ($stock < 0) {
        die("Stok produk tidak boleh negatif.");
    }

    /* =========================
       CEK KODE PRODUK
    ========================= */

    $checkCode = mysqli_prepare(
        $conn,
        "SELECT id FROM products WHERE product_code = ? LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $checkCode,
        "s",
        $product_code
    );

    mysqli_stmt_execute($checkCode);

    $resultCode = mysqli_stmt_get_result($checkCode);

    if (mysqli_num_rows($resultCode) > 0) {
        mysqli_stmt_close($checkCode);
        die("Kode produk sudah digunakan.");
    }

    mysqli_stmt_close($checkCode);

    /* =========================
       CEK KATEGORI
    ========================= */

    $checkCategory = mysqli_prepare(
        $conn,
        "SELECT id FROM categories WHERE id = ? LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $checkCategory,
        "i",
        $category_id
    );

    mysqli_stmt_execute($checkCategory);

    $resultCategory = mysqli_stmt_get_result($checkCategory);

    if (mysqli_num_rows($resultCategory) === 0) {
        mysqli_stmt_close($checkCategory);
        die("Kategori produk tidak ditemukan.");
    }

    mysqli_stmt_close($checkCategory);

    /* =========================
       SIMPAN PRODUK
    ========================= */

    $queryInsert = mysqli_prepare(
        $conn,
        "INSERT INTO products
        (
            product_code,
            name,
            category_id,
            price,
            stock,
            unit,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $queryInsert,
        "ssidiss",
        $product_code,
        $name,
        $category_id,
        $price,
        $stock,
        $unit,
        $description
    );

    if (mysqli_stmt_execute($queryInsert)) {

        mysqli_stmt_close($queryInsert);

        header("Location: produk.php");
        exit;

    } else {

        $error = mysqli_stmt_error($queryInsert);

        mysqli_stmt_close($queryInsert);

        die("Gagal menyimpan produk: " . $error);
    }
}

/* =========================
   AMBIL DATA KATEGORI
========================= */

$queryCategories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY name ASC"
);

if (!$queryCategories) {
    die("Gagal mengambil data kategori.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Produk - Coffee Inventory</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css?v=2"
    >

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                <img src="assets/icon/Logo coffee inventoy.png" alt="Coffee Inventory">
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
                <img src="assets/icon/Logo dashboard.png" alt="Dashboard">
            </span>
            Dashboard
            </a>

            <a
                href="produk.php"
                class="menu-item active"
            >
            <span class="menu-icon">
                <img src="assets/icon/Logo produk.png" alt="Produk">
            </span>
            Produk
            </a>

            <a
                href="stok.php"
                class="menu-item"
            >
            <span class="menu-icon">
                <img src="assets/icon/Logo stok.png" alt="Stok">
            </span>
            Stok
            </a>

            <a
                href="kategori.php"
                class="menu-item"
            >
            <span class="menu-icon">
                <img src="assets/icon/Logo kategori.png" alt="Kategori">
            </span>
            Kategori
            </a>

        </nav>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- HEADER -->
        <header class="topbar">

            <div>

                <h1>
                    Tambah Produk
                </h1>

                <p>
                    Tambahkan produk baru ke dalam inventory.
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
        <form method="POST" class="product-form">

            <div class="form-group">

                <label>
                    Kode Produk
                </label>

                <input
                    type="text"
                    name="product_code"
                    placeholder="Contoh: CF004"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Nama produk"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Kategori
                </label>

                <select
                    name="category_id"
                    required
                >

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <?php while ($category = mysqli_fetch_assoc($queryCategories)): ?>

                        <option
                            value="<?= (int) $category['id']; ?>"
                        >
                            <?= htmlspecialchars(
                                $category['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Harga
                </label>

                <input
                    type="number"
                    name="price"
                    placeholder="Contoh: 18000"
                    min="0"
                    step="1"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Stok
                </label>

                <input
                    type="number"
                    name="stock"
                    placeholder="Contoh: 20"
                    min="0"
                    step="1"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Satuan
                </label>

                <input
                    type="text"
                    name="unit"
                    placeholder="Contoh: cup"
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
                    placeholder="Deskripsi produk"
                ></textarea>

            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Simpan Produk
                </button>

                <a
                    href="produk.php"
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