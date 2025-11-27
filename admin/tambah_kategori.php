<?php
// admin/tambah_kategori.php
// Tahap 1: Kerangka Tampilan
require_once 'auth_guard.php';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Tambah Kategori Baru</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form Kosong -->
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="category_name" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="tambah_buku.php" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>