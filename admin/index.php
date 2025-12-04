<?php
session_start();
require_once 'auth_guard.php'; 
require_once '../classes/Book.php'; 
require_once '../classes/Database.php'; 

$book = new Book(); 
$books = $book->getAll(); 

// Dashboard Stats
$db = new Database(); $conn = $db->getConnection();
$total_buku = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_order = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_income = $conn->query("SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'")->fetchColumn();
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - AJAX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4 shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">ADMIN PANEL</a>
        <div class="d-flex">
            <a class="btn btn-light btn-sm fw-bold text-danger" href="../logout.php">LOGOUT</a>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body"><h3><?php echo $total_buku; ?></h3><small>Buku</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body"><h3><?php echo $total_order; ?></h3><small>Pesanan</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3 shadow-sm">
                    <div class="card-body text-dark"><h3>Rp <?php echo number_format($total_income); ?></h3><small>Pendapatan</small></div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Daftar Buku</h4>
            <div>
                <a href="tambah_kategori.php" class="btn btn-secondary shadow-sm me-2">+ Tambah Kategori</a>
                <a href="tambah_buku.php" class="btn btn-primary shadow-sm">+ Tambah Buku</a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Cover</th>
                                <th>Judul</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $b): ?>
                                <tr>
                                    <td class="ps-3">
                                        <img src="../assets/images/<?php echo htmlspecialchars($b['cover_image']); ?>" width="50" class="rounded">
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($b['title']); ?></td>
                                    <td>Rp <?php echo number_format($b['price'], 0, ',', '.'); ?></td>
                                    <td><?php echo $b['stock']; ?></td>
                                    <td class="text-center">
                                        <a href="edit_buku.php?id=<?php echo $b['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        
                                        <button class="btn btn-danger btn-sm btn-delete-ajax" 
                                                data-id="<?php echo $b['id']; ?>" 
                                                data-url="process_book.php">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/script.js"></script>
  </body>
</html>