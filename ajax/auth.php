<?php
session_start();
// Gunakan __DIR__ agar aman dipanggil dari mana saja
require_once __DIR__ . '/../classes/Auth.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid Request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $auth = new Auth();

    // --- REGISTER (AJAX) ---
    if ($action === 'register') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if (empty($username) || empty($password)) {
            $response['message'] = 'Semua kolom wajib diisi.';
        } elseif ($password !== $confirm) {
            $response['message'] = 'Konfirmasi password tidak cocok.';
        } elseif (strlen($password) < 4) {
            $response['message'] = 'Password minimal 4 karakter.';
        } else {
            if ($auth->register($username, $password)) {
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil! Mengalihkan ke login...';
                $response['redirect'] = 'login.php';
            } else {
                $response['message'] = 'Username sudah digunakan.';
            }
        }
    }
    
    // --- LOGIN (AJAX) ---
    elseif ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($auth->login($username, $password)) {
            $response['success'] = true;
            $response['message'] = 'Login berhasil!';
            $response['redirect'] = ($_SESSION['role'] == 'admin') ? 'admin/index.php' : 'index.php';
        } else {
            $response['message'] = 'Username atau Password salah!';
        }
    }
}

echo json_encode($response);
?>