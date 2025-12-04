<?php
require_once 'auth_guard.php';
include_once '../template/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Tambah Kategori Baru (AJAX)</h5>
                </div>
                <div class="card-body">
                    
                    <div class="alert-msg"></div>
                    
                    <form action="process_category.php" method="POST" class="ajax-form">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="category_name" class="form-control" placeholder="Misal: Komik, Sains..." required>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="tambah_buku.php" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../template/footer.php'; ?>