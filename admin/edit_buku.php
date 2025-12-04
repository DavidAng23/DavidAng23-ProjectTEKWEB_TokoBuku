<?php
require_once 'auth_guard.php'; 
require_once '../classes/Book.php';
require_once '../classes/Category.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; };

$book_id = $_GET['id'];
$bookObj = new Book();
$book_data = $bookObj->getById($book_id);
if (!$book_data) { header("Location: index.php"); exit; }

$catObj = new Category();
$categories = $catObj->getAll();

include_once '../template/header.php'; // Pakai header agar style terbawa
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-3">Edit Buku (AJAX)</h2>
    <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>

    <form action="process_book.php" method="POST" enctype="multipart/form-data" class="ajax-form">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $book_data['id']; ?>">
        <input type="hidden" name="old_cover_image" value="<?php echo $book_data['cover_image']; ?>">
        
        <div class="alert-msg"></div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
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
                            <img src="../assets/images/<?php echo $book_data['cover_image']; ?>" width="100" class="img-thumbnail mb-2">
                            <input type="file" class="form-control" name="cover_image">
                            <small class="text-muted">Biarkan kosong jika tidak ubah gambar.</small>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 text-white fw-bold"> Update Buku</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php include_once '../template/footer.php'; ?>