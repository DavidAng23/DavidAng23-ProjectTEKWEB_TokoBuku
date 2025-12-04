<?php
session_start(); 
require_once 'classes/Cart.php'; 
include_once 'template/header.php';

if (!isset($_SESSION['user_id'])) header("Location: login.php");

$cart = new Cart(); 
$uid = $_SESSION['user_id'];
$items = $cart->getCartItems($uid); 
$total = 0;
?>

<div class="container mt-5 mb-5 page-content">
    <h2>Keranjang Belanja</h2>
    
    <?php if(empty($items)): ?>
        <div class="alert alert-warning">
            Keranjang Anda kosong. <a href="index.php">Ayo belanja sekarang!</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th class="text-center">Jumlah</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $i): 
                        $sub = $i['price'] * $i['quantity']; 
                        $total += $sub; 
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="assets/images/<?php echo htmlspecialchars($i['cover_image']); ?>" width="50" class="me-3 rounded"> 
                                <span><?php echo htmlspecialchars($i['title']); ?></span>
                            </div>
                        </td>
                        <td>Rp <?php echo number_format($i['price']); ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary btn-cart-action" 
                                    data-action="update" 
                                    data-id="<?php echo $i['cart_id']; ?>" 
                                    data-qty="<?php echo $i['quantity'] - 1; ?>">
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <span class="mx-2 fw-bold"><?php echo $i['quantity']; ?></span>
                            
                            <button class="btn btn-sm btn-outline-secondary btn-cart-action" 
                                    data-action="update" 
                                    data-id="<?php echo $i['cart_id']; ?>" 
                                    data-qty="<?php echo $i['quantity'] + 1; ?>">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                        <td class="fw-bold">Rp <?php echo number_format($sub); ?></td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-cart-action" 
                                    data-action="remove" 
                                    data-id="<?php echo $i['cart_id']; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-3">
            <h4 class="mb-3">Total Belanja: Rp <?php echo number_format($total); ?></h4>
            <a href="checkout.php" class="btn btn-success btn-lg px-5 shadow">
                <i class="fas fa-credit-card me-2"></i> Checkout
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include_once 'template/footer.php'; ?>