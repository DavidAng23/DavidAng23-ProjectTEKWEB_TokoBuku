<?php
require_once '../classes/Category.php';
$category = new Category();
$categories = $category->getAll();

// PAKE HEADER KHUSUS ADMIN
include_once 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tambah Buku Baru</h2>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>

        <form action="process_book.php" method="POST" enctype="multipart/form-data" class="ajax-form">
            <input type="hidden" name="action" value="add">
            <div class="alert-msg"></div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white fw-bold">Informasi Buku</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" class="form-control" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold">Detail & Stok</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" name="price" required min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stock" required min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cover Image</label>
                                <input type="file" class="form-control" name="cover_image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="fas fa-save me-2"></i> Simpan Buku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once '../template/footer.php'; ?>