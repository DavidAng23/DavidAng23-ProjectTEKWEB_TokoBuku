<?php session_start(); require_once 'classes/Order.php'; include_once 'template/header.php';
$cart=new Cart(); $items=$cart->getCartItems($_SESSION['user_id']); $total=0; foreach($items as $i) $total+=$i['price']*$i['quantity'];
if(isset($_POST['bayar'])) {
    $order=new Order();
    if($order->checkout($_SESSION['user_id'], $_POST['nama']." (".$_POST['metode'].") - ".$_POST['alamat'])) {
        echo "<div class='container mt-5 text-center page-content'><h2 class='text-success'>Sukses!</h2><a href='index.php' class='btn btn-primary'>Home</a></div>";
        include_once 'template/footer.php'; exit;
    }
} ?>
<div class="container mt-5 mb-5 page-content">
    <div class="row">
        <div class="col-md-7">
            <div class="card p-4 shadow-sm">
                <h4>Pengiriman</h4>
                <form method="POST">
                    <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" value="<?php echo $_SESSION['username']; ?>" required></div>
                    <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label>Metode</label><select name="metode" class="form-select"><option>Transfer Bank</option><option>COD</option></select></div>
                    <button type="submit" name="bayar" class="btn btn-success w-100 py-2">BAYAR SEKARANG</button>
                </form>
            </div>
        </div>
        <div class="col-md-5"><div class="card p-4 bg-light border-0"><h4>Total: Rp <?php echo number_format($total); ?></h4><small>Untuk <?php echo count($items); ?> item</small></div></div>
    </div>
</div>
<?php include_once 'template/footer.php'; ?>