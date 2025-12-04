<?php
require_once 'auth_guard.php'; 
require_once '../classes/Category.php';

$category = new Category();
$categories = $category->getAll();

include_once '../template/header.php';
?>

<div class="container mt-4 mb-5">
    <h2>Tambah Buku Baru (AJAX)</h2>
    <a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Daftar Buku</a>

    <form action="process_book.php" method="POST" enctype="multipart/form-data" class="ajax-form">
        <input type="hidden" name="action" value="add">
        
        <div class="alert-msg"></div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
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

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Simpan Buku
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include_once '../template/footer.php'; ?> 