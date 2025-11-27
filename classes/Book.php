<?php
// classes/Book.php

// Panggil file konfigurasi database
require_once 'Database.php';

class Book {
    // Variabel untuk menyimpan koneksi database nanti
    private $conn;
    // Nama tabel yang akan kita pakai
    private $table_name = "books";

    // Constructor: Jalan otomatis saat class dipanggil
    public function __construct() {
        // Bikin objek database baru
        $db = new Database();
        // Simpan koneksinya ke variabel class ini
        $this->conn = $db->getConnection();
    }
}

// Fungsi ambil semua data buku (Read)
    public function getAll() {
        try {
            // Query select dengan join ke kategori
            $query = "SELECT b.*, c.category_name 
                      FROM " . $this->table_name . " b
                      LEFT JOIN categories c ON b.category_id = c.id
                      ORDER BY b.id DESC";

            // Siapkan statement
            $stmt = $this->conn->prepare($query);
            // Jalankan query
            $stmt->execute();
            // Ambil semua hasil dalam bentuk array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    // Fungsi ambil 1 buku berdasarkan ID
    public function getById($id) {
        try {
            // Query ambil 1 data saja
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            // Bind parameter ID biar aman
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            // Fetch satu baris saja
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }
?>