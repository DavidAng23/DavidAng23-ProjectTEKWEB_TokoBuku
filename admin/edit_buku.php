<?php

require_once 'auth_guard.php'; 
require_once '../classes/Book.php';
require_once '../classes/Category.php';

$message = "";

// Cek ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$book_id = $_GET['id'];
$bookObj = new Book();
$book_data = $bookObj->getById($book_id);

if (!$book_data) {
    header("Location: index.php");
    exit;
}

$catObj = new Category();
$categories = $catObj->getAll();

// LOGIKA UPDATE
if (isset($_POST['update'])) {
    if ($bookObj->update($_POST, $_FILES['cover_image'])) {
        header("Location: index.php?status=update_success");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Gagal update data. Silakan coba lagi.</div>';
        $book_data = $bookObj->getById($book_id);
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Buku - Admin</title>
    <!-- Bootstrap CSS (Link CDN agar aman) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- NAVBAR KHUSUS ADMIN (MERAH) -->
    <!-- Perhatikan link-nya menggunakan ../ untuk keluar dari folder admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4 shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-user-shield me-2"></i>ADMIN PANEL</a>
        
        <div class="d-flex">
            <!-- Link ke Web Utama -->
            <a class="btn btn-outline-light btn-sm me-2" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Web
            </a>
            <!-- Link Logout -->
            <a class="btn btn-light btn-sm fw-bold text-danger" href="../logout.php">
                LOGOUT
            </a>
        </div>
      </div>
    </nav>

    <div class="container mb-5">
        <h2 class="mb-3">Edit Buku</h2>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Buku</a>

        <?php echo $message; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?php echo $book_data['id']; ?>">
            <input type="hidden" name="old_cover_image" value="<?php echo $book_data['cover_image']; ?>">

            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Judul Buku</label>
                                <input type="text" class="form-control" id="title" name="title" required 
                                       value="<?php echo htmlspecialchars($book_data['title']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="author" class="form-label fw-bold">Penulis</label>
                                <input type="text" class="form-control" id="author" name="author" required
                                       value="<?php echo htmlspecialchars($book_data['author']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($book_data['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" class="form-control" id="price" name="price" required min="0"
                                       value="<?php echo htmlspecialchars($book_data['price']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="stock" class="form-label fw-bold">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0"
                                       value="<?php echo htmlspecialchars($book_data['stock']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Kategori</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" 
                                            <?php if ($cat['id'] == $book_data['category_id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3 text-center p-3 bg-light rounded border">
                                <label class="form-label small text-muted">Cover Saat Ini</label><br>
                                <!-- Perhatikan path gambar menggunakan ../ -->
                                <img src="../assets/images/<?php echo htmlspecialchars($book_data['cover_image']); ?>" class="img-thumbnail mb-2" width="120">
                            </div>
                            
                            <div class="mb-3">
                                <label for="cover_image" class="form-label fw-bold">Ganti Gambar</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                                <small class="text-muted d-block mt-1">Kosongkan jika tidak diganti.</small>
                            </div>

                            <hr>
                            <button type="submit" name="update" class="btn btn-warning w-100 text-white fw-bold py-2">
                                <i class="fas fa-save me-1"></i> Update Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script JS dengan CDN (Pasti Jalan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>