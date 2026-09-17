```php
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

/* =========================
   PROSES PERUBAHAN STOK
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id = (int) ($_POST['product_id'] ?? 0);
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    if ($product_id <= 0 || $jumlah <= 0) {
        header("Location: stok.php?status=invalid");
        exit;
    }

    // Ambil stok saat ini
    $queryProduct = mysqli_query(
        $conn,
        "SELECT stock FROM products WHERE id = $product_id"
    );

    $product = mysqli_fetch_assoc($queryProduct);

    if (!$product) {
        header("Location: stok.php?status=notfound");
        exit;
    }

    $stokSekarang = (int) $product['stock'];

    /* =========================
       TAMBAH STOK
    ========================= */

    if ($aksi === 'tambah') {

        $stokBaru = $stokSekarang + $jumlah;

    }

    /* =========================
       KURANGI STOK
    ========================= */

    elseif ($aksi === 'kurang') {

        // Stok tidak boleh menjadi minus
        if ($jumlah > $stokSekarang) {
            header("Location: stok.php?status=insufficient");
            exit;
        }

        $stokBaru = $stokSekarang - $jumlah;

    }

    else {

        header("Location: stok.php?status=invalid");
        exit;
    }


    /* =========================
       UPDATE STOK
    ========================= */

    $update = mysqli_query(
        $conn,
        "UPDATE products
         SET stock = $stokBaru
         WHERE id = $product_id"
    );

    if ($update) {

        header("Location: stok.php?status=success");
        exit;

    }

    header("Location: stok.php?status=error");
    exit;
}


/* =========================
   AMBIL DATA PRODUK
========================= */

$query = mysqli_query($conn, "

    SELECT
        products.*,
        categories.name AS category_name

    FROM products

    INNER JOIN categories
        ON products.category_id = categories.id

    ORDER BY products.name ASC

");


/* =========================
   STATISTIK TOTAL STOK
========================= */

$queryTotal = mysqli_query(
    $conn,
    "SELECT SUM(stock) AS total FROM products"
);

$rowTotal = mysqli_fetch_assoc($queryTotal);

$totalStock = (int) ($rowTotal['total'] ?? 0);


/* =========================
   STATISTIK STOK MENIPIS
========================= */

$queryLow = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products
     WHERE stock > 0
     AND stock <= 5"
);

$rowLow = mysqli_fetch_assoc($queryLow);

$lowStock = (int) ($rowLow['total'] ?? 0);


/* =========================
   STATISTIK STOK HABIS
========================= */

$queryEmpty = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products
     WHERE stock = 0"
);

$rowEmpty = mysqli_fetch_assoc($queryEmpty);

$emptyStock = (int) ($rowEmpty['total'] ?? 0);

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Stok - Coffee Inventory</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css?v=2"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
    ></script>

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
                class="menu-item active"
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
                    Stok
                </h1>

                <p>
                    Kelola persediaan produk Coffee Inventory
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


        <!-- STATISTIK -->
        <section class="stats stock-stats">


            <!-- TOTAL STOK -->
            <div class="stat-card">

                <div class="stat-icon">

                    <img
                        src="assets/icon/Logo stok.png"
                        alt="Total Stok"
                    >

                </div>

                <div>

                    <p>
                        Total Stok
                    </p>

                    <h2>
                        <?= $totalStock; ?>
                    </h2>

                </div>

            </div>


            <!-- STOK MENIPIS -->
            <div class="stat-card">

                <div class="stat-icon">

                    <img
                        src="assets/icon/Logo Stok Menipis.png"
                        alt="Stok Menipis"
                    >

                </div>

                <div>

                    <p>
                        Stok Menipis
                    </p>

                    <h2>
                        <?= $lowStock; ?>
                    </h2>

                </div>

            </div>


            <!-- STOK HABIS -->
            <div class="stat-card warning">

                <div class="stat-icon">

                    <img
                        src="assets/icon/Logo Stok Habis.png"
                        alt="Stok Habis"
                    >

                </div>

                <div>

                    <p>
                        Stok Habis
                    </p>

                    <h2>
                        <?= $emptyStock; ?>
                    </h2>

                </div>

            </div>


        </section>


        <!-- DAFTAR STOK -->
        <section class="content-card">


            <div class="card-header">

                <div>

                    <h2>
                        Daftar Stok Produk
                    </h2>

                    <p>
                        Kelola jumlah persediaan setiap produk.
                    </p>

                </div>


                <!-- SEARCH & FILTER -->
                <div class="stock-filter">

                    <input
                        type="text"
                        id="searchStock"
                        placeholder="Cari produk..."
                    >


                    <select id="filterStock">

                        <option value="all">
                            Semua Status
                        </option>

                        <option value="aman">
                            Aman
                        </option>

                        <option value="menipis">
                            Menipis
                        </option>

                        <option value="habis">
                            Habis
                        </option>

                    </select>

                </div>

            </div>


            <!-- TABLE -->
            <div class="table-container">

                <table class="product-table">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Kode</th>

                            <th>Produk</th>

                            <th>Kategori</th>

                            <th>Stok</th>

                            <th>Satuan</th>

                            <th>Status</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    while (
                        $product = mysqli_fetch_assoc($query)
                    ):

                        $stock = (int) $product['stock'];

                    ?>

                        <tr
                            class="stock-row"

                            data-name="<?= htmlspecialchars(
                                (string) $product['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"

                            data-code="<?= htmlspecialchars(
                                (string) $product['product_code'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"

                            data-status="<?=
                                $stock == 0
                                    ? 'habis'
                                    : (
                                        $stock <= 5
                                            ? 'menipis'
                                            : 'aman'
                                    );
                            ?>"
                        >


                            <!-- NOMOR -->
                            <td>
                                <?= $no++; ?>
                            </td>


                            <!-- KODE -->
                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        (string) $product['product_code'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </strong>

                            </td>


                            <!-- PRODUK -->
                            <td>

                                <?= htmlspecialchars(
                                    (string) $product['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <!-- KATEGORI -->
                            <td>

                                <?= htmlspecialchars(
                                    (string) $product['category_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <!-- STOK -->
                            <td>

                                <strong>
                                    <?= $stock; ?>
                                </strong>

                            </td>


                            <!-- SATUAN -->
                            <td>

                                <?= htmlspecialchars(
                                    (string) $product['unit'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <!-- STATUS -->
                            <td>

                                <?php if ($stock == 0): ?>

                                    <span class="stock-status stock-empty">
                                        Habis
                                    </span>

                                <?php elseif ($stock <= 5): ?>

                                    <span class="stock-status stock-low">
                                        Menipis
                                    </span>

                                <?php else: ?>

                                    <span class="stock-status stock-safe">
                                        Aman
                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- AKSI -->
                            <td>

                                <button
                                    type="button"
                                    class="stock-btn stock-add"
                                    data-id="<?= (int) $product['id']; ?>"
                                    data-aksi="tambah"
                                    data-nama="<?= htmlspecialchars(
                                        (string) $product['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >
                                    + Tambah
                                </button>


                                <button
                                    type="button"
                                    class="stock-btn stock-reduce"
                                    data-id="<?= (int) $product['id']; ?>"
                                    data-aksi="kurang"
                                    data-nama="<?= htmlspecialchars(
                                        (string) $product['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >
                                    − Kurangi
                                </button>

                            </td>

                        </tr>


                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>


<!-- FORM PERUBAHAN STOK -->
<form
    method="POST"
    id="stockForm"
    style="display:none;"
>

    <input
        type="hidden"
        name="product_id"
        id="product_id"
    >

    <input
        type="hidden"
        name="jumlah"
        id="jumlah"
    >

    <input
        type="hidden"
        name="aksi"
        id="aksi"
    >

</form>


<!-- JAVASCRIPT STOK -->
<script src="assets/js/stok.js"></script>

</body>
</html>
```
