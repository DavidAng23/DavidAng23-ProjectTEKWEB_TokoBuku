<?php
require_once 'auth_guard.php';
require_once __DIR__ . '/../classes/Category.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['category_name'] ?? '');
    
    if (!empty($cat_name)) {
        $category = new Category();
        if ($category->create($cat_name)) {
            $response['success'] = true;
            $response['message'] = "Kategori berhasil dibuat!";
            // $response['redirect'] = "tambah_buku.php"; // Opsional jika mau redirect
        } else {
            $response['message'] = "Gagal! Kategori mungkin sudah ada.";
        }
    } else {
        $response['message'] = "Nama kategori tidak boleh kosong.";
    }
}

echo json_encode($response);
?>