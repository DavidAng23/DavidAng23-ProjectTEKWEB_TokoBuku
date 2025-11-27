<?php
// admin/tambah_buku.php

// 1. PANGGIL FILE PENDUKUNG
// auth_guard.php: Memastikan user yang masuk adalah admin (Login Check)
require_once 'auth_guard.php'; 
// Book.php: Berisi logika create() untuk menyimpan buku ke database
require_once '../classes/Book.php';
// Category.php: Berisi logika untuk mengambil daftar kategori
require_once '../classes/Category.php';

// Variabel untuk menampung pesan error atau sukses
$message = "";

// 2. PERSIAPAN DATA DROPDOWN
// Membuat objek Category baru
$category = new Category();
// Mengambil semua data kategori dari DB untuk ditampilkan di <select> html
$categories = $category->getAll();

// --- 3. LOGIKA UTAMA (SAAT TOMBOL SUBMIT DITEKAN) ---
// Cek apakah ada request POST dengan nama tombol 'submit'
if (isset($_POST['submit'])) {
    
    // Membuat objek Book baru
    $book = new Book();
    
    // Panggil method create() yang ada di class Book
    // Parameter 1: $_POST berisi data teks (Judul, Harga, Stok, dll)
    // Parameter 2: $_FILES['cover_image'] berisi file gambar yang diupload user
    if ($book->create($_POST, $_FILES['cover_image'])) {
        
        // JIKA SUKSES:
        // Redirect (pindah halaman) kembali ke index.php dengan parameter sukses
        header("Location: index.php?status=create_success");
        exit; // Hentikan script agar redirect berjalan mulus
        
    } else {
        // JIKA GAGAL:
        // Isi variabel message dengan alert Bootstrap berwarna merah
        $message = '<div class="alert alert-danger">Gagal menambahkan buku. Cek kembali data dan file Anda.</div>';
    }
}
// --- SELESAI LOGIKA ---

// Memanggil template header (Navbar & CSS)
include_once '../template/header.php';
?>

<h2>Tambah Buku Baru</h2>
<a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Daftar Buku</a>

<?php echo $message; ?>

<form action="tambah_buku.php" method="POST" enctype="multipart/form-data">
    <div class="row">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <div class="mt-1">
                            <small>
                                Kategori belum ada? 
                                <a href="tambah_kategori.php" class="text-decoration-none fw-bold">
                                    + Buat Kategori Baru
                                </a>
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Gambar Sampul (Cover)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                        <small class="form-text">Kosongkan jika tidak ingin upload (akan pakai default.jpg)</small>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary w-100">Simpan Buku</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
// Memanggil template footer (Script JS & Copyright)
include_once '../template/footer.php';
?>