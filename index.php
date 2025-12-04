<?php
// index.php
require_once 'classes/Book.php';
require_once 'classes/Category.php'; 
include_once 'template/header.php';

$book = new Book();
$books = $book->getAll();

// Ambil data kategori untuk dropdown
$categoryObj = new Category();
$categories = $categoryObj->getAll();
?>

<div class="container mt-5 mb-5 page-content">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-5">
            <h2 class="fw-bold text-dark mb-1">Katalog Buku</h2>
            <p class="text-muted">Temukan koleksi buku terbaik kami.</p>
        </div>
        
        <div class="col-md-7">
            <div class="row g-2">
                <div class="col-md-4">
                    <select class="form-select" id="category-filter">
                        <option value="">Semua Kategori</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8">
                    <input class="form-control" type="search" id="search-input" placeholder="Cari judul atau penulis..." autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" id="book-container">
        
        <?php foreach ($books as $b): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 position-relative">
                    <span class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 m-3 rounded-pill small shadow-sm" style="z-index:2;">
                        <?php echo htmlspecialchars($b['category_name'] ?? 'Umum'); ?>
                    </span>
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
                        <div class="mt-auto">
                            <span class="h5 fw-bold text-primary">
                                Rp <?php echo number_format($b['price'], 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                        <div class="d-grid gap-2">
                            <a href="detail_buku.php?id=<?php echo $b['id']; ?>" class="btn btn-outline-secondary btn-sm">Detail</a>
                            <?php if ($b['stock'] > 0): ?>
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
        
    </div>
</div>

<?php include_once 'template/footer.php'; ?>