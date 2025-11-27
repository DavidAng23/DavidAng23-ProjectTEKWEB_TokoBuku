<?php
// admin/edit_buku.php

// --- BAGIAN 1: PERSIAPAN DATA ---

// 1. Panggil Penjaga & Class
require_once 'auth_guard.php'; 
require_once '../classes/Book.php';
require_once '../classes/Category.php';

$message = ""; // Variabel untuk menampung pesan error/sukses

// 2. Cek Parameter ID di URL
// Jika user membuka file ini tanpa ?id=... maka tendang keluar
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$book_id = $_GET['id'];
$bookObj = new Book();

// 3. Ambil Data Buku Lama
// Kita perlu data lama untuk ditampilkan di form agar user bisa melihat apa yang mau diedit
$book_data = $bookObj->getById($book_id);

// Jika buku tidak ditemukan (misal ID 9999 padahal cuma ada 5 buku), tendang keluar
if (!$book_data) {
    header("Location: index.php");
    exit;
}

// 4. Ambil Data Kategori
// Untuk mengisi pilihan di Dropdown Kategori
$catObj = new Category();
$categories = $catObj->getAll();


// --- BAGIAN 2: LOGIKA UPDATE (SAAT TOMBOL DITEKAN) ---

// Cek apakah tombol dengan name="update" sudah ditekan?
if (isset($_POST['update'])) {
    
    // Panggil method update() dari Class Book
    // Kita kirim $_POST (data teks) dan $_FILES (data gambar baru jika ada)
    if ($bookObj->update($_POST, $_FILES['cover_image'])) {
        
        // JIKA SUKSES:
        // Redirect ke halaman index dengan pesan sukses
        header("Location: index.php?status=update_success");
        exit;
        
    } else {
        // JIKA GAGAL:
        // Tampilkan pesan error di halaman ini
        $message = '<div class="alert alert-danger">Gagal update data. Silakan coba lagi.</div>';
        
        // Refresh data buku agar form tidak kosong jika terjadi error
        $book_data = $bookObj->getById($book_id);
    }
}

// Panggil Template Header
include_once '../template/header.php';
?>

<h2>Edit Buku</h2>
<a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Daftar Buku</a>

<?php echo $message; ?>

<form method="POST" enctype="multipart/form-data">
    
    <input type="hidden" name="id" value="<?php echo $book_data['id']; ?>">
    <input type="hidden" name="old_cover_image" value="<?php echo $book_data['cover_image']; ?>">

    <div class="row">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="title" name="title" required 
                               value="<?php echo htmlspecialchars($book_data['title']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="author" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author" required
                               value="<?php echo htmlspecialchars($book_data['author']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($book_data['description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" required min="0"
                               value="<?php echo htmlspecialchars($book_data['price']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required min="0"
                               value="<?php echo htmlspecialchars($book_data['stock']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php if ($cat['id'] == $book_data['category_id']) echo 'selected'; ?>>
                                    
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3 text-center">
                        <label class="form-label">Cover Saat Ini</label><br>
                        <img src="../assets/images/<?php echo htmlspecialchars($book_data['cover_image']); ?>" class="img-thumbnail mb-2" width="120">
                    </div>
                    
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Ganti Gambar (Opsional)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>

                    <button type="submit" name="update" class="btn btn-warning w-100 text-white">Update Perubahan</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include_once '../template/footer.php'; ?>