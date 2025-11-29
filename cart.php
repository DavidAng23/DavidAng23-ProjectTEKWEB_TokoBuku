<?php
session_start(); require_once 'classes/Cart.php'; include_once 'template/header.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");
$cart = new Cart(); $uid = $_SESSION['user_id'];
if (isset($_GET['action'])) {
    if ($_GET['action']=='remove') $cart->removeItem($_GET['id'], $uid);
    if ($_GET['action']=='update') $cart->updateQuantity($_GET['id'], max(1, (int)$_GET['qty']), $uid);
    echo "<script>window.location.href='cart.php';</script>";
}
$items = $cart->getCartItems($uid); $total = 0;
?>
<div class="container mt-5 mb-5 page-content">
    <h2>Keranjang</h2>
    <?php if(empty($items)): ?><div class="alert alert-warning">Kosong. <a href="index.php">Belanja</a></div><?php else: ?>
    <table class="table align-middle">
        <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Total</th><th></th></tr></thead>
        <tbody>
            <?php foreach($items as $i): $sub=$i['price']*$i['quantity']; $total+=$sub; ?>
            <tr>
                <td><img src="assets/images/<?php echo $i['cover_image']; ?>" width="50"> <?php echo $i['title']; ?></td>
                <td>Rp <?php echo number_format($i['price']); ?></td>
                <td>
                    <a href="cart.php?action=update&id=<?php echo $i['cart_id']; ?>&qty=<?php echo $i['quantity']-1; ?>" class="btn btn-sm btn-light border">-</a>
                    <span class="mx-2"><?php echo $i['quantity']; ?></span>
                    <a href="cart.php?action=update&id=<?php echo $i['cart_id']; ?>&qty=<?php echo $i['quantity']+1; ?>" class="btn btn-sm btn-light border">+</a>
                </td>
                <td>Rp <?php echo number_format($sub); ?></td>
                <td><a href="cart.php?action=remove&id=<?php echo $i['cart_id']; ?>" class="text-danger"><i class="fas fa-trash"></i></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-end"><h4 class="mb-3">Total: Rp <?php echo number_format($total); ?></h4><a href="checkout.php" class="btn btn-success btn-lg px-5">Checkout</a></div>
    <?php endif; ?>
</div>
<?php include_once 'template/footer.php'; ?>