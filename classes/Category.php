<?php
// classes/Category.php

// Memanggil file Database.php untuk mendapatkan koneksi ke database
require_once 'Database.php';

class Category {
    // Properti untuk menyimpan objek koneksi database
    private $conn;
    // Nama tabel yang digunakan di database
    private $table_name = "categories";

    // Constructor: Method yang otomatis dijalankan saat class ini dipanggil
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Method untuk mengambil semua data kategori (untuk Dropdown)
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY category_name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function create($category_name) {
        try {
            // Query INSERT untuk menyimpan data baru
            $query = "INSERT INTO " . $this->table_name . " (category_name) VALUES (:name)";
            
            // Siapkan statement
            $stmt = $this->conn->prepare($query);
            
            // Bersihkan data (opsional, tapi bindParam sudah cukup aman)
            $category_name = htmlspecialchars(strip_tags($category_name));
            
            // Masukkan parameter
            $stmt->bindParam(':name', $category_name);
            
            // Eksekusi query
            if ($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            // Error biasanya terjadi jika nama kategori sudah ada (Duplicate Entry)
            return false;
        }
    }
}
?>