<?php
// classes/Auth.php
require_once __DIR__ . '/Database.php';

class Auth {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function register($username, $password) {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Query Insert
            $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                return true; 
            } else {
                return false; 
            }

        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($username, $password) {
        try {
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    return true;
                }
            }
            return false; 
            
        } catch (PDOException $e) {
            return false;
        }
    }   
}
?>