console.log("STOK.JS BERHASIL DIMUAT");


/* =========================
   UBAH STOK
========================= */

function ubahStok(id, aksi, namaProduk) {

    const judul =
        aksi === 'tambah'
            ? 'Tambah Stok'
            : 'Kurangi Stok';


    Swal.fire({

        title: judul,

        text: namaProduk,

        input: 'number',

        inputPlaceholder: 'Masukkan jumlah',

        inputAttributes: {
            min: 1,
            step: 1
        },

        showCancelButton: true,

        confirmButtonText:
            aksi === 'tambah'
                ? 'Tambah Stok'
                : 'Kurangi Stok',

        cancelButtonText: 'Batal',

        inputValidator: (value) => {

            if (!value || Number(value) <= 0) {

                return 'Masukkan jumlah stok yang valid.';

            }

        }

    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('product_id').value = id;

            document.getElementById('jumlah').value =
                result.value;

            document.getElementById('aksi').value =
                aksi;

            document.getElementById('stockForm').submit();

        }

    });

}


/* =========================
   TOMBOL TAMBAH / KURANGI
========================= */

document.addEventListener('DOMContentLoaded', function () {

    const stockButtons =
        document.querySelectorAll('.stock-btn');


    stockButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const id =
                this.dataset.id;

            const aksi =
                this.dataset.aksi;

            const namaProduk =
                this.dataset.nama;


            ubahStok(
                id,
                aksi,
                namaProduk
            );

        });

    });


    /* =========================
       SEARCH
    ========================= */

    const searchStock =
        document.getElementById('searchStock');


    const filterStock =
        document.getElementById('filterStock');


    function filterStok() {

        const keyword =
            searchStock
                ? searchStock.value.toLowerCase().trim()
                : '';


        const statusFilter =
            filterStock
                ? filterStock.value
                : 'all';


        const rows =
            document.querySelectorAll('.stock-row');


        rows.forEach(function (row) {

            const name =
                (row.dataset.name || '').toLowerCase();


            const code =
                (row.dataset.code || '').toLowerCase();


            const rowStatus =
                row.dataset.status || '';


            const cocokNama =
                name.includes(keyword) ||
                code.includes(keyword);


            const cocokStatus =
                statusFilter === 'all' ||
                rowStatus === statusFilter;


            if (
                cocokNama &&
                cocokStatus
            ) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    }


    /* =========================
       EVENT SEARCH
    ========================= */

    if (searchStock) {

        searchStock.addEventListener(
            'input',
            filterStok
        );

    }


    /* =========================
       EVENT FILTER
    ========================= */

    if (filterStock) {

        filterStock.addEventListener(
            'change',
            filterStok
        );

    }

});


/* =========================
   NOTIFIKASI
========================= */

const params =
    new URLSearchParams(
        window.location.search
    );


const status =
    params.get('status');


if (status === 'success') {

    Swal.fire({

        icon: 'success',

        title: 'Berhasil',

        text: 'Stok produk berhasil diperbarui.',

        timer: 1800,

        showConfirmButton: false

    });

}


if (status === 'insufficient') {

    Swal.fire({

        icon: 'warning',

        title: 'Stok Tidak Cukup',

        text:
            'Jumlah stok yang dikurangi melebihi stok saat ini.'

    });

}


if (status === 'invalid') {

    Swal.fire({

        icon: 'error',

        title: 'Data Tidak Valid',

        text:
            'Silakan masukkan data stok yang benar.'

    });

}


if (status === 'notfound') {

    Swal.fire({

        icon: 'error',

        title: 'Produk Tidak Ditemukan',

        text:
            'Data produk tidak ditemukan.'

    });

}


if (status === 'error') {

    Swal.fire({

        icon: 'error',

        title: 'Gagal',

        text:
            'Stok gagal diperbarui.'

    });

}