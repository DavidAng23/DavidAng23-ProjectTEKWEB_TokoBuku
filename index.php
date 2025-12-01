<?php
// index.php
require_once 'classes/Book.php';
require_once 'classes/Category.php'; // (Opsional) Jika ingin filter kategori nanti
include_once 'template/header.php';

$book = new Book();
$keyword = "";

// LOGIKA PENCARIAN
// Cek apakah ada parameter 'cari' di URL
if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    $keyword = $_GET['cari'];
    // Panggil method search() (pastikan sudah ada di Class Book)
    $books = $book->search($keyword); 
    $judul_halaman = "Hasil Pencarian: '" . htmlspecialchars($keyword) . "'";
} else {
    // Jika tidak ada pencarian, tampilkan semua buku
    $books = $book->getAll();
    $judul_halaman = "Katalog Buku";
}
?>

<div class="container mt-5 mb-5 page-content">
    
    <!-- Bagian Header & Pencarian -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1"><?php echo $judul_halaman; ?></h2>
            <p class="text-muted">Temukan koleksi buku terbaik kami.</p>
        </div>
        <div class="col-md-6">
            <!-- FORM PENCARIAN -->
            <form action="index.php" method="GET" class="d-flex">
                <input class="form-control me-2" type="search" name="cari" placeholder="Cari judul buku atau penulis..." aria-label="Search" value="<?php echo htmlspecialchars($keyword); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                <?php if(!empty($keyword)): ?>
                    <a href="index.php" class="btn btn-outline-secondary ms-2" title="Reset Pencarian"><i class="fas fa-sync-alt"></i></a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- GRID BUKU (Sama seperti sebelumnya) -->
    <div class="row g-4">
        <?php if (empty($books)): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3"><i class="fas fa-search fa-3x text-muted opacity-25"></i></div>
                <h4 class="text-muted">Yah, buku yang dicari tidak ditemukan.</h4>
                <a href="index.php" class="btn btn-primary mt-3">Lihat Semua Buku</a>
            </div>
        <?php else: ?>
            <?php foreach ($books as $b): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 position-relative">
                        <!-- Badge Kategori -->
                        <span class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 m-3 rounded-pill small shadow-sm" style="z-index:2;">
                            <?php echo htmlspecialchars($b['category_name'] ?? 'Umum'); ?>
                        </span>

                        <!-- Gambar -->
                        <div class="overflow-hidden bg-light text-center">
                            <img src="assets/images/<?php echo htmlspecialchars($b['cover_image']); ?>" 
                                 class="card-img-top" 
                                 style="height: 280px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($b['title']); ?>">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-1 text-truncate" title="<?php echo htmlspecialchars($b['title']); ?>">
                                <?php echo htmlspecialchars($b['title']); ?>
                            </h6>
                            <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars($b['author']); ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="h5 fw-bold text-primary mb-0">
                                    Rp <?php echo number_format($b['price'], 0, ',', '.'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                            <div class="d-grid gap-2">
                                <a href="detail_buku.php?id=<?php echo $b['id']; ?>" class="btn btn-outline-secondary btn-sm">Detail</a>
                                
                                <?php if ($b['stock'] > 0): ?>
                                    <!-- Tombol Add to Cart dengan Data ID -->
                                    <button class="btn btn-success btn-sm btn-add-to-cart" data-book-id="<?php echo $b['id']; ?>">
                                        <i class="fas fa-plus me-1"></i> Keranjang
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-light btn-sm text-muted" disabled>Stok Habis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'template/footer.php'; ?>