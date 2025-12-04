<?php
require_once 'auth_guard.php';
// Gunakan __DIR__ untuk path yang pasti benar
require_once __DIR__ . '/../classes/Book.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $book = new Book();

    // --- ADD BOOK ---
    if ($action === 'add') {
        if ($book->create($_POST, $_FILES['cover_image'])) {
            $response['success'] = true;
            $response['message'] = "Buku berhasil ditambahkan!";
        } else {
            $response['message'] = "Gagal menyimpan buku.";
        }
    }
    // --- EDIT BOOK (BARU) ---
    elseif ($action === 'update') {
        // $_FILES['cover_image'] opsional di Edit
        if ($book->update($_POST, $_FILES['cover_image'])) {
            $response['success'] = true;
            $response['message'] = "Data buku berhasil diperbarui!";
            $response['redirect'] = "index.php"; // Redirect ke tabel admin
        } else {
            $response['message'] = "Gagal mengupdate buku.";
        }
    }
    // --- DELETE BOOK ---
    elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($book->delete($id)) {
            $response['success'] = true;
            $response['message'] = "Data berhasil dihapus.";
        } else {
            $response['message'] = "Gagal menghapus data.";
        }
    }
}

echo json_encode($response);
?>