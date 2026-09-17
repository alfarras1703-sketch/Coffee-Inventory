<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

// Ambil data kategori
$query = mysqli_query($conn, "
    SELECT *
    FROM categories
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kategori - Coffee Inventory</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css?v=2"
    >

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    Kategori
                </h1>

                <p>
                    Kelola kategori produk Coffee Inventory
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


        <!-- CATEGORY CARD -->
        <section class="content-card">


            <div class="card-header">

                <div>

                    <h2>
                        Daftar Kategori
                    </h2>

                    <p>
                        Kategori produk yang tersimpan dalam inventory.
                    </p>

                </div>


                <a
                    href="tambah-kategori.php"
                    class="btn-primary"
                >
                    + Tambah Kategori
                </a>

            </div>


            <!-- TABLE -->
            <div class="table-container">

                <table class="product-table">

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama Kategori
                            </th>

                            <th>
                                Deskripsi
                            </th>

                            <th>
                                Dibuat
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    while ($category = mysqli_fetch_assoc($query)) {

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>


                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        $category['name']
                                    ); ?>
                                </strong>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $category['description']
                                ); ?>

                            </td>


                            <td>

                                <?= date(
                                    'd-m-Y',
                                    strtotime($category['created_at'])
                                ); ?>

                            </td>


                            <td>

                                <a
                                    href="edit-kategori.php?id=<?= $category['id']; ?>"
                                    class="action-edit"
                                >
                                    Edit
                                </a>


                                <a
                                    href="hapus-kategori.php?id=<?= $category['id']; ?>"
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

        title: 'Hapus Kategori?',

        text: 'Data kategori yang dihapus tidak dapat dikembalikan.',

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