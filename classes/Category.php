<?php
// classes/Category.php

// Memanggil file Database.php untuk mendapatkan koneksi ke database
require_once 'Database.php';

class Category {
    // Properti untuk menyimpan objek koneksi database
    private $conn;
    // Nama tabel yang digunakan di database
    private $table_name = "categories";

    // Constructor: Method yang otomatis dijalankan saat class ini dipanggil (di-instansiasi)
    public function __construct() {
        // Membuat objek baru dari class Database
        $db = new Database();
        // Mengambil koneksi PDO dan menyimpannya ke properti $this->conn
        $this->conn = $db->getConnection();
    }

    // Method untuk mengambil semua data kategori (digunakan untuk mengisi opsi Dropdown)
    public function getAll() {
        try {
            // Query SQL untuk memilih semua kolom dari tabel categories
            // ORDER BY category_name ASC berfungsi mengurutkan nama kategori dari A ke Z
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY category_name ASC";
            
            // Menyiapkan statement query (Prepare) untuk dieksekusi
            $stmt = $this->conn->prepare($query);
            
            // Menjalankan query tersebut di database
            $stmt->execute();
            
            // Mengembalikan hasil data dalam bentuk Array Asosiatif (key sesuai nama kolom)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Jika terjadi error pada database, kembalikan array kosong agar program tidak crash
            return [];
        }
    }
}
?>