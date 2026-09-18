# Coffee Inventory

Aplikasi web sederhana untuk mengelola data inventory produk pada Coffee Inventory. Aplikasi ini digunakan untuk mengelola data produk, kategori, dan stok melalui antarmuka berbasis web.

## Deskripsi

Coffee Inventory merupakan aplikasi inventory berbasis web yang dibuat untuk membantu proses pengelolaan data produk dan stok.

Aplikasi menyediakan beberapa fitur utama seperti:

* Dashboard informasi inventory
* Pengelolaan data produk
* Penambahan, pengeditan, dan penghapusan produk
* Pengelolaan kategori
* Pengelolaan stok produk
* Pencarian dan filter data stok
* Validasi input
* Konfirmasi penghapusan menggunakan SweetAlert2
* Notifikasi hasil proses pengelolaan stok

## Teknologi yang Digunakan

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* SweetAlert2
* XAMPP
* Visual Studio Code
* Git / GitHub
* Google Chrome

## Struktur Project

```text
coffee-inventory/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── js/
│   │   └── stok.js
│   │
│   └── icon/
│
├── config/
│   └── database.php
│
├── index.php
├── login.php
├── logout.php
├── produk.php
├── tambah-produk.php
├── edit-produk.php
├── hapus-produk.php
├── kategori.php
├── tambah-kategori.php
├── edit-kategori.php
├── hapus-kategori.php
├── stok.php
├── update-stok.php
├── README.md
└── coffee_inventory.sql
```

## Database

Database yang digunakan dalam project ini adalah:

```text
coffee_inventory
```

File database disediakan dalam:

```text
coffee_inventory.sql
```

Import file SQL tersebut melalui phpMyAdmin sebelum menjalankan aplikasi.

## Cara Menjalankan Project

### 1. Install dan jalankan XAMPP

Aktifkan:

* Apache
* MySQL

### 2. Letakkan project

Simpan folder project di:

```text
C:\xampp\htdocs\coffee-inventory
```

### 3. Buat database

Buka phpMyAdmin melalui:

```text
http://localhost/phpmyadmin
```

Buat database dengan nama:

```text
coffee_inventory
```

Kemudian import file:

```text
coffee_inventory.sql
```

### 4. Periksa konfigurasi database

Pastikan konfigurasi pada:

```text
config/database.php
```

sesuai dengan konfigurasi MySQL pada XAMPP.

### 5. Jalankan aplikasi

Buka Google Chrome dan akses:

```text
http://localhost/coffee-inventory/
```

### 6. Login

Gunakan halaman login yang tersedia pada aplikasi untuk masuk ke sistem.

## Fitur Utama

### Dashboard

Menampilkan ringkasan informasi inventory seperti jumlah produk, kategori, total stok, dan produk dengan stok menipis.

### Produk

Digunakan untuk:

* Melihat data produk
* Menambah produk
* Mengedit produk
* Menghapus produk

### Kategori

Digunakan untuk:

* Melihat kategori
* Menambah kategori
* Mengedit kategori
* Menghapus kategori

### Stok

Digunakan untuk:

* Melihat stok produk
* Menambah stok
* Mengurangi stok
* Mencari produk berdasarkan nama atau kode
* Memfilter data berdasarkan status stok

## Library

Project menggunakan **SweetAlert2** untuk menampilkan dialog konfirmasi dan notifikasi pada beberapa proses aplikasi.

## Pengujian

Pengujian aplikasi dilakukan melalui browser Google Chrome dengan memeriksa fungsi aplikasi, validasi input, interaksi pengguna, serta JavaScript melalui Developer Tools.

## Pengembang

**Coffee Inventory**

Project Uji Kompetensi Junior Web Programming.
