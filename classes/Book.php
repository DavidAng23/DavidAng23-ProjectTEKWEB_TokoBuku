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
?>