<?php
// admin/tambah_buku.php
// Ceritanya logic PHP belum dibuat, cuma kerangka tampilan dulu
require_once 'auth_guard.php';
include_once '../template/header.php';
?>

<h2>Tambah Buku Baru</h2>
<a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Daftar Buku</a>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="price">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Simpan Draft</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include_once '../template/footer.php'; ?>