<?php
session_start();
require_once __DIR__ . '/../classes/Order.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $metode = $_POST['metode'] ?? '';

    if (empty($nama) || empty($alamat)) {
        echo json_encode(['success' => false, 'message' => 'Nama dan Alamat wajib diisi.']);
        exit;
    }

    $full_address = "$nama ($metode) - $alamat";
    $order = new Order();

    // Proses Checkout
    if ($order->checkout($_SESSION['user_id'], $full_address)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Pembayaran Berhasil! Terima kasih.',
            'redirect' => 'index.php' // Redirect ke home setelah sukses
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memproses pesanan (Keranjang mungkin kosong).']);
    }
}
?>