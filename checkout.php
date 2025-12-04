<?php 
session_start(); 
require_once 'classes/Cart.php'; 
include_once 'template/header.php';

if (!isset($_SESSION['user_id'])) header("Location: login.php");

$cart = new Cart(); 
$items = $cart->getCartItems($_SESSION['user_id']); 
$total = 0; 
foreach($items as $i) $total += $i['price'] * $i['quantity'];

if (empty($items)) {
    echo "<script>window.location.href='index.php';</script>"; exit;
}
?>

<div class="container mt-5 mb-5 page-content">
    <div class="row">
        <div class="col-md-7">
            <div class="card p-4 shadow-sm border-0">
                <h4 class="mb-3">Form Pengiriman & Pembayaran</h4>
                
                <div class="alert-msg"></div>

                <form action="ajax/checkout_process.php" method="POST" class="ajax-form">
                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Contoh No. 123..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode" class="form-select">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="COD">COD (Bayar di Tempat)</option>
                            <option value="E-Wallet">E-Wallet (OVO/Gopay)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fas fa-check-circle me-2"></i> KONFIRMASI PEMBAYARAN
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card p-4 bg-light border-0 shadow-sm">
                <h4 class="mb-3">Ringkasan Pesanan</h4>
                <ul class="list-group list-group-flush mb-3">
                    <?php foreach($items as $i): ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0"><?php echo htmlspecialchars($i['title']); ?></h6>
                                <small class="text-muted">x <?php echo $i['quantity']; ?></small>
                            </div>
                            <span class="text-muted">Rp <?php echo number_format($i['price'] * $i['quantity']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">Total (IDR)</span>
                    <strong class="fs-5 text-primary">Rp <?php echo number_format($total); ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'template/footer.php'; ?>