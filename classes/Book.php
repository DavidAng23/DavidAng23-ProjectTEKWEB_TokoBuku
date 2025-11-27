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

    // Fungsi tambah buku baru
    public function create($data, $file) {
        try {
            // Upload gambar dulu
            $image_filename = $this->uploadCover($file);
            if ($image_filename === false) return false;

            // Query insert data
            $query = "INSERT INTO " . $this->table_name . "
                      (title, author, description, price, stock, category_id, cover_image)
                      VALUES (:title, :author, :description, :price, :stock, :cat_id, :img)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':title' => $data['title'],
                ':author' => $data['author'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':cat_id' => $data['category_id'],
                ':img' => $image_filename
            ]);
            return true;
        } catch (PDOException $e) { return false; }
    }

    // Helper upload gambar
    private function uploadCover($file) {
        if (!isset($file['name']) || $file['error'] != UPLOAD_ERR_OK) return 'default.jpg';
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = "cover_" . time() . "." . $ext;
        $target = "../assets/images/" . $new_filename;
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) return false;
        if (move_uploaded_file($file["tmp_name"], $target)) return $new_filename;
        return false;
    }
?>