<?php
// ajax/search_book.php
require_once __DIR__ . '/../classes/Book.php';

$book = new Book();
$keyword = $_GET['keyword'] ?? '';
$category_id = $_GET['category'] ?? ''; // Tangkap ID Kategori

// Panggil fungsi search dengan 2 parameter
$books = $book->search($keyword, $category_id);

if (empty($books)) {
    echo '
    <div class="col-12 text-center py-5">
        <div class="mb-3"><i class="fas fa-search fa-3x text-muted opacity-25"></i></div>
        <h4 class="text-muted">Buku tidak ditemukan.</h4>
    </div>';
} else {
    foreach ($books as $b) {
        $price = number_format($b['price'], 0, ',', '.');
        $cat = htmlspecialchars($b['category_name'] ?? 'Umum');
        $img = htmlspecialchars($b['cover_image']);
        $title = htmlspecialchars($b['title']);
        $auth = htmlspecialchars($b['author']);
        $id = $b['id'];
        
        $btn_cart = ($b['stock'] > 0) 
            ? "<button class='btn btn-success btn-sm btn-add-to-cart' data-book-id='$id'><i class='fas fa-plus me-1'></i> Keranjang</button>" 
            : "<button class='btn btn-light btn-sm text-muted' disabled>Stok Habis</button>";

        echo "
        <div class='col-6 col-md-4 col-lg-3'>
            <div class='card h-100 shadow-sm border-0 position-relative'>
                <span class='position-absolute top-0 start-0 bg-primary text-white px-3 py-1 m-3 rounded-pill small shadow-sm' style='z-index:2;'>
                    $cat
                </span>
                <div class='overflow-hidden bg-light text-center'>
                    <img src='assets/images/$img' class='card-img-top' style='height: 280px; object-fit: cover;' alt='$title'>
                </div>
                <div class='card-body d-flex flex-column'>
                    <h6 class='card-title fw-bold mb-1 text-truncate' title='$title'>$title</h6>
                    <p class='card-text text-muted small mb-3'>$auth</p>
                    <div class='mt-auto'>
                        <span class='h5 fw-bold text-primary'>Rp $price</span>
                    </div>
                </div>
                <div class='card-footer bg-white border-0 pt-0 pb-3 px-3'>
                    <div class='d-grid gap-2'>
                        <a href='detail_buku.php?id=$id' class='btn btn-outline-secondary btn-sm'>Detail</a>
                        $btn_cart
                    </div>
                </div>
            </div>
        </div>";
    }
}
?>