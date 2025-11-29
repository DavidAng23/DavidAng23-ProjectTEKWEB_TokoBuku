<?php require_once 'classes/Database.php'; include_once 'template/header.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");
$db=new Database(); $conn=$db->getConnection();
$stmt=$conn->prepare("SELECT * FROM orders WHERE user_id=:u ORDER BY order_date DESC"); $stmt->execute([':u'=>$_SESSION['user_id']]);
$orders=$stmt->fetchAll(PDO::FETCH_ASSOC); ?>
<div class="container mt-5 mb-5 page-content">
    <h2>Riwayat Pesanan</h2>
    <?php foreach($orders as $o): ?>
    <div class="card mb-3 shadow-sm">
        <div class="card-header d-flex justify-content-between bg-white">
            <span class="fw-bold">Order #<?php echo $o['id']; ?></span>
            <span class="badge bg-success"><?php echo $o['status']; ?></span>
        </div>
        <div class="card-body">
            <p class="mb-1">Total: <b>Rp <?php echo number_format($o['total_amount']); ?></b></p>
            <small class="text-muted"><?php echo $o['order_date']; ?> | <?php echo $o['shipping_address']; ?></small>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include_once 'template/footer.php'; ?>