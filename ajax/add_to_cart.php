<?php
session_start(); require_once '../classes/Database.php';
$res = ['success' => false, 'message' => 'Error'];
if (isset($_SESSION['user_id']) && isset($_POST['book_id'])) {
    $uid = $_SESSION['user_id']; $bid = $_POST['book_id']; $qty = max(1, (int)($_POST['quantity'] ?? 1));
    $db = new Database(); $conn = $db->getConnection();
    
    $book = $conn->prepare("SELECT stock FROM books WHERE id=:id"); $book->execute([':id'=>$bid]);
    $stock = $book->fetchColumn();
    $cart = $conn->prepare("SELECT quantity FROM cart WHERE user_id=:u AND book_id=:b"); $cart->execute([':u'=>$uid, ':b'=>$bid]);
    $in_cart = $cart->fetchColumn() ?: 0;

    if (($in_cart + $qty) > $stock) $res['message'] = "Stok kurang. Sisa: $stock";
    else {
        if ($in_cart > 0) $conn->prepare("UPDATE cart SET quantity = quantity + :q WHERE user_id=:u AND book_id=:b")->execute([':q'=>$qty, ':u'=>$uid, ':b'=>$bid]);
        else $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (:u, :b, :q)")->execute([':u'=>$uid, ':b'=>$bid, ':q'=>$qty]);
        $res['success'] = true;
    }
} else $res['message'] = 'Login dulu.';
echo json_encode($res);
?>