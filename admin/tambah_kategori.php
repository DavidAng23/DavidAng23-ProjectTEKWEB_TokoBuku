<?php
// PAKE HEADER ADMIN
include_once 'header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tambah Kategori Baru</h5>
            </div>
            <div class="card-body p-4">
                
                <div class="alert-msg"></div>
                
                <form action="process_category.php" method="POST" class="ajax-form">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control form-control-lg" placeholder="Contoh: Fiksi Ilmiah, Sejarah..." required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check me-2"></i> Simpan Kategori
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary">
                            Batal & Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../template/footer.php'; ?>