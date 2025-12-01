<?php
require_once __DIR__ . '/Database.php';

class Cart {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function addItem($uid, $bid, $qty) {
        try {
            $query_check = "SELECT * FROM cart WHERE user_id = :uid AND book_id = :bid";
            $stmt = $this->conn->prepare($query_check);
            $stmt->execute([':uid' => $uid, ':bid' => $bid]);

            if ($stmt->rowCount() > 0) {
                // Update quantity
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $new_qty = $row['quantity'] + $qty;
                
                $q = "UPDATE cart SET quantity = :qty WHERE id = :id";
                $this->conn->prepare($q)->execute([':qty' => $new_qty, ':id' => $row['id']]);
            } else {
                // Insert baru
                $q = "INSERT INTO cart (user_id, book_id, quantity) VALUES (:uid, :bid, :qty)";
                $this->conn->prepare($q)->execute([':uid' => $uid, ':bid' => $bid, ':qty' => $qty]);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCartItems($user_id) {
        try {
            $query = "SELECT c.id as cart_id, c.quantity, b.id as book_id, b.title, b.price, b.cover_image 
                      FROM cart c
                      JOIN books b ON c.book_id = b.id
                      WHERE c.user_id = :user_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function removeItem($cart_id, $user_id) {
        try {
            $query = "DELETE FROM cart WHERE id = :cid AND user_id = :uid";
            return $this->conn->prepare($query)->execute([':cid' => $cart_id, ':uid' => $user_id]);
        } catch (PDOException $e) { return false; }
    }

    public function updateQuantity($cart_id, $quantity, $user_id) {
        if ($quantity < 1) return false;
        try {
            $query = "UPDATE cart SET quantity = :qty WHERE id = :cid AND user_id = :uid";
            return $this->conn->prepare($query)->execute([':qty' => $quantity, ':cid' => $cart_id, ':uid' => $user_id]);
        } catch (PDOException $e) { return false; }
    }
}
?>