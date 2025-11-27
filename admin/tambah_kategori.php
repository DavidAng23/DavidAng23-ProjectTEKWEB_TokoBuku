<?php
// admin/tambah_kategori.php

// 1. KEAMANAN & CLASS
// Panggil penjaga pintu, hanya admin yang boleh masuk
require_once 'auth_guard.php';
// Panggil class Category untuk akses database kategori
require_once '../classes/Category.php';

// Variabel untuk menampung pesan sukses/gagal
$message = "";

// 2. LOGIKA PENYIMPANAN
// Cek apakah tombol submit dengan nama 'submit_category' sudah ditekan?
if (isset($_POST['submit_category'])) {
    
    // Ambil data yang diketik user di input 'category_name'
    $cat_name = $_POST['category_name'];
    
    // Validasi sederhana: Pastikan tidak kosong
    if (!empty($cat_name)) {
        
        // Buat objek Category baru
        $category = new Category();
        
        // Panggil method create() di Class Category
        if ($category->create($cat_name)) {
            // JIKA SUKSES:
            // Tampilkan pesan sukses warna hijau
            // Berikan link untuk kembali ke halaman tambah_buku.php
            $message = '<div class="alert alert-success">
                            Kategori <b>'.htmlspecialchars($cat_name).'</b> berhasil dibuat! 
                            <a href="tambah_buku.php">Kembali ke Tambah Buku</a>
                        </div>';
        } else {
            // JIKA GAGAL (Misal error database):
            $message = '<div class="alert alert-danger">Gagal! Kategori mungkin sudah ada.</div>';
        }
    } else {
        // Jika user memaksa submit kosong
        $message = '<div class="alert alert-danger">Nama kategori tidak boleh kosong.</div>';
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Gunakan kolom medium (col-md-6) agar form tidak terlalu lebar -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Tambah Kategori Baru</h5>
                    </div>
                    <div class="card-body">
                        <!-- Tampilkan pesan notifikasi di sini -->
                        <?php echo $message; ?>
                        
                        <!-- Form Input -->
                        <form action="tambah_kategori.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <!-- Input text sederhana -->
                                <input type="text" name="category_name" class="form-control" placeholder="Misal: Komik, Sains..." required>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <!-- Tombol Batal mengarah kembali ke form tambah buku -->
                                <a href="tambah_buku.php" class="btn btn-outline-secondary">Batal / Kembali</a>
                                
                                <!-- Tombol Simpan -->
                                <button type="submit" name="submit_category" class="btn btn-success">Simpan Kategori</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>