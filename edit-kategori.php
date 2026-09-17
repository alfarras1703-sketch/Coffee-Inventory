```php
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

// Ambil ID kategori
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID kategori tidak valid.");
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name === '') {
        die("Nama kategori wajib diisi.");
    }

    $queryUpdate = mysqli_query($conn, "
        UPDATE categories SET
            name = '$name',
            description = '$description'
        WHERE id = $id
    ");

    if ($queryUpdate) {
        header("Location: kategori.php");
        exit;
    } else {
        die("Gagal memperbarui kategori: " . mysqli_error($conn));
    }
}

// Ambil data kategori
$queryCategory = mysqli_query($conn, "
    SELECT *
    FROM categories
    WHERE id = $id
");

$category = mysqli_fetch_assoc($queryCategory);

if (!$category) {
    die("Kategori tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori - Coffee Inventory</title>

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
                    Edit Kategori
                </h1>

                <p>
                    Ubah informasi kategori Coffee Inventory.
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
                        Form Edit Kategori
                    </h2>

                    <p>
                        Perbarui data kategori yang dipilih.
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
                        value="<?= htmlspecialchars($category['name']); ?>"
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
                    ><?= htmlspecialchars($category['description']); ?></textarea>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Simpan Perubahan
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
```
