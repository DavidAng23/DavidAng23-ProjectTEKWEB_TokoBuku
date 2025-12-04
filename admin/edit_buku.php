<?php
require_once '../classes/Book.php';
require_once '../classes/Category.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; };
$bookObj = new Book();
$book_data = $bookObj->getById($_GET['id']);
if (!$book_data) { header("Location: index.php"); exit; }

$catObj = new Category();
$categories = $catObj->getAll();

// PAKE HEADER ADMIN
include_once 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Data Buku</h2>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
            </a>
        </div>

        <form action="process_book.php" method="POST" enctype="multipart/form-data" class="ajax-form">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $book_data['id']; ?>">
            <input type="hidden" name="old_cover_image" value="<?php echo $book_data['cover_image']; ?>">
            
            <div class="alert-msg"></div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" name="title" required value="<?php echo htmlspecialchars($book_data['title']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" class="form-control" name="author" required value="<?php echo htmlspecialchars($book_data['author']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="5"><?php echo htmlspecialchars($book_data['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" name="price" required value="<?php echo $book_data['price']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stock" required value="<?php echo $book_data['stock']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="category_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $book_data['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3 text-center">
                                <label class="form-label d-block text-start">Cover Saat Ini:</label>
                                <img src="../assets/images/<?php echo $book_data['cover_image']; ?>" class="img-thumbnail mb-2" style="max-height: 150px;">
                                <input type="file" class="form-control" name="cover_image">
                                <small class="text-muted d-block mt-1 text-start">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 text-white fw-bold">
                                <i class="fas fa-edit me-1"></i> Update Buku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include_once '../template/footer.php'; ?>