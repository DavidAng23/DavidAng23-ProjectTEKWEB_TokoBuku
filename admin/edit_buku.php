<?php
// admin/edit_buku.php

// 1. KEAMANAN & KONEKSI
require_once 'auth_guard.php'; // Pastikan user sudah login sebagai admin
require_once '../classes/Book.php'; // Panggil Class Book untuk akses database
require_once '../classes/Category.php'; // Panggil Class Category untuk dropdown

// 2. CEK ID DI URL
// Kita cek apakah di alamat web ada ?id=... (Contoh: edit_buku.php?id=5)
if (!isset($_GET['id'])) {
    // Jika tidak ada ID, kita tidak tahu buku apa yang mau diedit, jadi kembalikan ke index
    header("Location: index.php");
    exit;
}

// 3. AMBIL DATA BUKU
$id = $_GET['id']; // Simpan ID dari URL ke variabel
$bookObj = new Book(); // Bikin objek buku
$book_data = $bookObj->getById($id); // Minta data buku berdasarkan ID tersebut

// Cek apakah bukunya ketemu?
if (!$book_data) {
    // Kalau ID-nya ngawur (tidak ada di database), kembalikan ke index
    header("Location: index.php");
    exit;
}

// 4. AMBIL DATA KATEGORI
// Kita butuh daftar semua kategori untuk ditampilkan di pilihan dropdown
$catObj = new Category();
$categories = $catObj->getAll();

// Panggil template header (Navbar dll)
include_once '../template/header.php';
?>

<h2>Edit Buku: <?php echo htmlspecialchars($book_data['title']); ?></h2>
<a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Daftar Buku</a>

<form method="POST" enctype="multipart/form-data">
    
    <input type="hidden" name="id" value="<?php echo $book_data['id']; ?>">
    <input type="hidden" name="old_cover_image" value="<?php echo $book_data['cover_image']; ?>">

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($book_data['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="author" class="form-control" required 
                               value="<?php echo htmlspecialchars($book_data['author']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($book_data['description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required 
                               value="<?php echo htmlspecialchars($book_data['price']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control" required 
                               value="<?php echo htmlspecialchars($book_data['stock']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach($categories as $c): ?>
                                <option value="<?php echo $c['id']; ?>" 
                                    <?php echo ($c['id'] == $book_data['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label">Cover Saat Ini</label><br>
                        <img src="../assets/images/<?php echo $book_data['cover_image']; ?>" class="img-thumbnail mb-2" width="120">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ganti Cover (Opsional)</label>
                        <input type="file" name="cover_image" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>

                    <button type="submit" name="update" class="btn btn-warning w-100 text-white">Update Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include_once '../template/footer.php'; ?>