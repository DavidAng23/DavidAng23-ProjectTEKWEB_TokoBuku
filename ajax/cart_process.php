<?php
session_start();
require_once __DIR__ . '/../classes/Cart.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login kembali.']);
    exit;
}

$cart = new Cart();
$uid = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$id = $_POST['cart_id'] ?? 0;

if ($action === 'update') {
    $qty = (int)($_POST['qty'] ?? 1);
    // Update jumlah
    if ($cart->updateQuantity($id, $qty, $uid)) {
        echo json_encode(['success' => true, 'message' => 'Keranjang diupdate']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update']);
    }
} 
elseif ($action === 'remove') {
    // Hapus item
    if ($cart->removeItem($id, $uid)) {
        echo json_encode(['success' => true, 'message' => 'Item dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus']);
    }
} 
else {
    echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
}
?>