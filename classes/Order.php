<?php
require_once __DIR__ . '/Database.php'; require_once __DIR__ . '/Cart.php';
class Order {
    private $conn;
    public function __construct() { $db=new Database(); $this->conn=$db->getConnection(); }
    public function checkout($uid, $addr) {
        try {
            $this->conn->beginTransaction();
            $cart=new Cart(); $items=$cart->getCartItems($uid);
            if(empty($items)) throw new Exception("Empty");
            $total=0; foreach($items as $i) $total+=($i['price']*$i['quantity']);
            
            $stmt=$this->conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, status) VALUES (:u, :t, :a, 'pending')");
            $stmt->execute([':u'=>$uid, ':t'=>$total, ':a'=>$addr]);
            $oid=$this->conn->lastInsertId();

            $det=$this->conn->prepare("INSERT INTO order_details (order_id, book_id, quantity, price_at_purchase) VALUES (:o, :b, :q, :p)");
            $stk=$this->conn->prepare("UPDATE books SET stock=stock-:q WHERE id=:b");

            foreach($items as $i) {
                $det->execute([':o'=>$oid, ':b'=>$i['book_id'], ':q'=>$i['quantity'], ':p'=>$i['price']]);
                $stk->execute([':q'=>$i['quantity'], ':b'=>$i['book_id']]);
            }
            $this->conn->prepare("DELETE FROM cart WHERE user_id=:u")->execute([':u'=>$uid]);
            $this->conn->commit(); return true;
        } catch(Exception $e) { $this->conn->rollBack(); return false; }
    }
}
?>