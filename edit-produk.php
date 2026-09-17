<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

/* =========================
   AMBIL ID PRODUK
========================= */

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id <= 0) {
    die("ID produk tidak valid.");
}

/* =========================
   AMBIL DATA PRODUK
========================= */

$queryProduct = mysqli_prepare(
    $conn,
    "SELECT * FROM products WHERE id = ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $queryProduct,
    "i",
    $id
);

mysqli_stmt_execute($queryProduct);

$resultProduct = mysqli_stmt_get_result($queryProduct);

$product = mysqli_fetch_assoc($resultProduct);

mysqli_stmt_close($queryProduct);

if (!$product) {
    die("Produk tidak ditemukan.");
}

/* =========================
   PROSES UPDATE PRODUK
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
       KECUALI PRODUK SENDIRI
    ========================= */

    $checkCode = mysqli_prepare(
        $conn,
        "SELECT id
         FROM products
         WHERE product_code = ?
         AND id != ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $checkCode,
        "si",
        $product_code,
        $id
    );

    mysqli_stmt_execute($checkCode);

    $resultCode = mysqli_stmt_get_result($checkCode);

    if (mysqli_num_rows($resultCode) > 0) {

        mysqli_stmt_close($checkCode);

        die("Kode produk sudah digunakan oleh produk lain.");
    }

    mysqli_stmt_close($checkCode);

    /* =========================
       CEK KATEGORI
    ========================= */

    $checkCategory = mysqli_prepare(
        $conn,
        "SELECT id
         FROM categories
         WHERE id = ?
         LIMIT 1"
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
       UPDATE PRODUK
    ========================= */

    $queryUpdate = mysqli_prepare(
        $conn,
        "UPDATE products SET
            product_code = ?,
            name = ?,
            category_id = ?,
            price = ?,
            stock = ?,
            unit = ?,
            description = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $queryUpdate,
        "ssidissi",
        $product_code,
        $name,
        $category_id,
        $price,
        $stock,
        $unit,
        $description,
        $id
    );

    if (mysqli_stmt_execute($queryUpdate)) {

        mysqli_stmt_close($queryUpdate);

        header("Location: produk.php");
        exit;

    } else {

        $error = mysqli_stmt_error($queryUpdate);

        mysqli_stmt_close($queryUpdate);

        die("Gagal memperbarui produk: " . $error);
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

    <title>Edit Produk - Coffee Inventory</title>

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
                class="menu-item active"
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
                class="menu-item"
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

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- HEADER -->
        <header class="topbar">

            <div>

                <h1>
                    Edit Produk
                </h1>

                <p>
                    Ubah informasi produk Coffee Inventory.
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
                        Form Edit Produk
                    </h2>

                    <p>
                        Perbarui data produk yang dipilih.
                    </p>

                </div>

            </div>


            <form method="POST" class="product-form">

                <div class="form-group">

                    <label>
                        Kode Produk
                    </label>

                    <input
                        type="text"
                        name="product_code"
                        value="<?= htmlspecialchars(
                            $product['product_code'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
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
                        value="<?= htmlspecialchars(
                            $product['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
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
                                <?= (
                                    (int) $category['id']
                                    === (int) $product['category_id']
                                ) ? 'selected' : ''; ?>
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
                        value="<?= htmlspecialchars(
                            $product['price'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
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
                        value="<?= htmlspecialchars(
                            $product['stock'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
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
                        value="<?= htmlspecialchars(
                            $product['unit'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
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
                    ><?= htmlspecialchars(
                        $product['description'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?></textarea>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Simpan Perubahan
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