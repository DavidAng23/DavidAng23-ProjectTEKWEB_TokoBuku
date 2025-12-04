<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'auth_guard.php'; // Pastikan aman

// Path CSS otomatis (Mundur satu folder karena ini di dalam admin)
$path_css = '../assets/css/style.css';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?php echo $path_css; ?>" rel="stylesheet">
  </head>
  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4 shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-user-shield me-2"></i>ADMIN PANEL
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <span class="nav-link text-white">Halo, Admin</span>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-light btn-sm fw-bold text-danger" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> LOGOUT
                    </a>
                </li>
            </ul>
        </div>
      </div>
    </nav>

    <div class="container pb-5">