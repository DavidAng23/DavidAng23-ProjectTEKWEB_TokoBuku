<?php
// classes/Book.php

// Panggil file database.php agar bisa konek ke MySQL
require_once 'Database.php';

class Book {
    // Properti untuk menyimpan objek koneksi
    private $conn;
    // Tentukan nama tabel yang dipakai biar gak salah ketik
    private $table_name = "books";

    // Constructor: Dijalankan pertama kali saat class Book dipanggil
    public function __construct() {
        // Buat objek database baru
        $db = new Database();
        // Simpan koneksinya ke variabel $conn
        $this->conn = $db->getConnection();
    }

    // --- FITUR 1: READ (TAMPILKAN SEMUA DATA) ---
    public function getAll() {
        try {
            // Siapkan query SELECT semua kolom
            // Pakai LEFT JOIN ke tabel categories biar dapat nama kategorinya
            $query = "SELECT b.*, c.category_name 
                      FROM " . $this->table_name . " b
                      LEFT JOIN categories c ON b.category_id = c.id
                      ORDER BY b.id DESC"; // Urutkan dari yang paling baru
            
            // Prepare statement untuk keamanan
            $stmt = $this->conn->prepare($query);
            // Jalankan query
            $stmt->execute();
            // Kembalikan semua hasil dalam bentuk array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Kalau error, balikin array kosong biar web gak crash
            return [];
        }
    }

    // --- FITUR 2: SEARCH (PENCARIAN) ---
    public function search($keyword) {
        try {
            // Query mirip getAll, tapi tambah WHERE LIKE
            $query = "SELECT b.*, c.category_name 
                      FROM " . $this->table_name . " b
                      LEFT JOIN categories c ON b.category_id = c.id
                      WHERE b.title LIKE :keyword OR b.author LIKE :keyword
                      ORDER BY b.id DESC";
            
            $stmt = $this->conn->prepare($query);
            
            // Tambah tanda % di depan belakang keyword untuk pencarian bebas
            $keyword = "%{$keyword}%";
            
            // Masukkan keyword ke parameter query
            $stmt->bindParam(':keyword', $keyword);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // --- FITUR 3: AMBIL DETAIL 1 BUKU ---
    public function getById($id) {
        try {
            // Query ambil 1 data berdasarkan ID
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            // Bind ID yang diminta
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Pakai fetch() karena cuma butuh satu baris
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    // --- FITUR 4: CREATE (TAMBAH DATA) ---
    public function create($data, $file) {
        try {
            // Panggil fungsi uploadCover dulu buat urus gambarnya
            $image_filename = $this->uploadCover($file);
            
            // Kalau upload gagal, stop proses
            if ($image_filename === false) return false;

            // Query INSERT data baru
            $query = "INSERT INTO " . $this->table_name . "
                      (title, author, description, price, stock, category_id, cover_image)
                      VALUES (:title, :author, :description, :price, :stock, :cat_id, :img)";
            
            $stmt = $this->conn->prepare($query);
            
            // Masukkan data form ke dalam query (Binding)
            $stmt->execute([
                ':title' => $data['title'],
                ':author' => $data['author'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':cat_id' => $data['category_id'],
                ':img' => $image_filename // Masukkan nama file gambar
            ]);
            return true;
        } catch (PDOException $e) { return false; }
    }

    // --- FITUR 5: UPDATE (EDIT DATA) ---
    public function update($data, $file) {
        try {
            // Default gambar adalah gambar lama
            $image_filename = $data['old_cover_image'];
            $new_upload = false;

            // Cek apakah user upload gambar baru?
            if (isset($file['name']) && $file['error'] == UPLOAD_ERR_OK) {
                // Upload gambar baru
                $new_filename = $this->uploadCover($file);
                if ($new_filename) {
                    // Update nama file jadi yang baru
                    $image_filename = $new_filename;
                    $new_upload = true;
                }
            }

            // Query UPDATE data
            $query = "UPDATE " . $this->table_name . "
                      SET title=:title, author=:author, description=:desc,
                          price=:price, stock=:stock, category_id=:cat_id, cover_image=:img
                      WHERE id=:id";
            
            $stmt = $this->conn->prepare($query);
            
            // Eksekusi update
            $stmt->execute([
                ':title' => $data['title'],
                ':author' => $data['author'],
                ':desc' => $data['description'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':cat_id' => $data['category_id'],
                ':img' => $image_filename,
                ':id' => $data['id']
            ]);

            // Hapus gambar lama biar server gak penuh, TAPI cuma kalau ada upload baru
            if ($new_upload && $data['old_cover_image'] != 'default.jpg') {
                $this->deleteCoverFile($data['old_cover_image']);
            }
            return true;
        } catch (PDOException $e) { return false; }
    }

    // --- FITUR 6: DELETE (HAPUS DATA) ---
    public function delete($id) {
        try {
            // Ambil data buku dulu buat tau nama gambarnya
            $book = $this->getById($id);
            if (!$book) return false;

            // Query DELETE
            $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
            $stmt->bindParam(':id', $id);
            
            // Kalau sukses dihapus di database
            if ($stmt->execute()) {
                // Hapus juga file gambarnya dari folder
                $this->deleteCoverFile($book['cover_image']);
                return true;
            }
            return false;
        } catch (PDOException $e) { return false; }
    }

    // --- HELPER: LOGIKA UPLOAD GAMBAR ---
    private function uploadCover($file) {
        // Cek error dasar
        if (!isset($file['name']) || $file['error'] != UPLOAD_ERR_OK) return 'default.jpg';
        
        // Ambil ekstensi file dan kecilkan hurufnya (JPG -> jpg)
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        // Bikin nama file unik pakai waktu sekarang biar gak bentrok
        $new_filename = "cover_" . time() . "." . $ext;
        
        // Tentukan folder tujuan
        $target = "../assets/images/" . $new_filename;

        // Validasi: Harus gambar
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) return false;
        
        // Pindahkan file ke folder tujuan
        if (move_uploaded_file($file["tmp_name"], $target)) return $new_filename;
        return false;
    }

    // --- HELPER: HAPUS FILE FISIK ---
    private function deleteCoverFile($filename) {
        // Hapus cuma kalau file ada dan bukan default.jpg
        if (!empty($filename) && $filename != 'default.jpg' && file_exists("../assets/images/$filename")) {
            unlink("../assets/images/$filename");
        }
    }
}
?>