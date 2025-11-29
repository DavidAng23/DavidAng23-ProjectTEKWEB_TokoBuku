<?php
require_once __DIR__ . '/Database.php';
class Cart {
    private $conn;
    public function __construct() { $db = new Database(); $this->conn = $db->getConnection(); }
    public function addItem($uid, $bid, $qty) {
        $chk = $this->conn->prepare("SELECT * FROM cart WHERE user_id=:u AND book_id=:b");
        $chk->execute([':u'=>$uid, ':b'=>$bid]);
        if($chk->rowCount()>0) {
            $row = $chk->fetch(PDO::FETCH_ASSOC);
            return $this->conn->prepare("UPDATE cart SET quantity=:q WHERE id=:id")->execute([':q'=>$row['quantity']+$qty, ':id'=>$row['id']]);
        }
        return $this->conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (:u, :b, :q)")->execute([':u'=>$uid, ':b'=>$bid, ':q'=>$qty]);
    }
    public function getCartItems($uid) {
        $stmt = $this->conn->prepare("SELECT c.id as cart_id, c.quantity, b.id as book_id, b.title, b.price, b.cover_image, b.stock FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = :uid");
        $stmt->execute([':u'=>$uid]); return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateQuantity($cid, $qty, $uid) {
        if($qty<1) return false;
        return $this->conn->prepare("UPDATE cart SET quantity=:q WHERE id=:c AND user_id=:u")->execute([':q'=>$qty, ':c'=>$cid, ':u'=>$uid]);
    }
    public function removeItem($cid, $uid) {
        return $this->conn->prepare("DELETE FROM cart WHERE id=:c AND user_id=:u")->execute([':c'=>$cid, ':u'=>$uid]);
    }
}
?>