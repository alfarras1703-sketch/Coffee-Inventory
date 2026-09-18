```php
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$query = mysqli_query($conn, "
    SELECT 
        products.*,
        categories.name AS category_name
    FROM products
    INNER JOIN categories 
        ON products.category_id = categories.id
    ORDER BY products.id DESC
");

$products = [];

while ($product = mysqli_fetch_assoc($query)) {
    $products[] = $product;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produk - Coffee Inventory</title>

    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            <a href="index.php" class="menu-item">
            <span class="menu-icon">
                <img src="assets/icon/Logo dashboard.png" alt="Dashboard">
            </span>
                Dashboard
            </a>

            <a href="produk.php" class="menu-item active">
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
                <h1>Produk</h1>
                <p>Kelola data produk Coffee Inventory</p>
            </div>

            <div class="admin-profile">

                <div class="avatar">A</div>

                <div>
                    <strong>Administrator</strong>
                    <small>Admin</small>
                </div>

            </div>

        </header>


        <!-- PRODUCT CARD -->
        <section class="content-card">

            <div class="card-header">

                <div>
                    <h2>Daftar Produk</h2>
                    <p>Data produk yang tersimpan dalam inventory.</p>
                </div>

                <a href="tambah-produk.php" class="btn-primary">
                 + Tambah Produk
                </a>

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
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    foreach ($products as $product) {
                    ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($product['product_code']); ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($product['name']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($product['category_name']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($product['price'], 0, ',', '.'); ?>
                            </td>

                            <td>
                                <?= $product['stock']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($product['unit']); ?>
                            </td>

                            <td>

<a href="edit-produk.php?id=<?= $product['id']; ?>" class="action-edit">
    Edit
</a>

<a
    href="hapus-produk.php?id=<?= $product['id']; ?>"
    class="action-delete"
    onclick="return confirmDelete(event, this.href);"
>
    Hapus
</a>
                            </td>

                        </tr>

                    <?php
                    }
                    ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script>
function confirmDelete(event, url) {

    event.preventDefault();

    Swal.fire({
        title: 'Hapus Produk?',
        text: 'Data produk yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = url;
        }

    });

    return false;
}
</script>

</body>
</html>
```
