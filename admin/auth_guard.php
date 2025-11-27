<?php
// admin/auth_guard.php

// Cek status session di server saat ini
// PHP_SESSION_NONE artinya session belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    // Jika belum aktif, maka kita jalankan session_start()
    // Ini wajib agar kita bisa membaca variabel $_SESSION
    session_start();
}

// --- PEMERIKSAAN 1: APAKAH USER SUDAH LOGIN? ---
// Kita cek apakah variabel session 'user_id' sudah terisi atau belum
// Tanda seru (!) artinya "TIDAK" (Jika user_id TIDAK ada)
if (!isset($_SESSION['user_id'])) {
    
    // Jika user belum login, paksa pindah (redirect) ke halaman login
    header("Location: ../login.php");
    
    // Hentikan eksekusi script seketika agar halaman admin tidak sempat dimuat
    exit;
}

// --- PEMERIKSAAN 2: APAKAH LEVELNYA ADMIN? ---
// Kita cek apakah role yang tersimpan di session BUKAN 'admin'
// Tanda (!==) artinya "TIDAK SAMA DENGAN"
if ($_SESSION['role'] !== 'admin') {
    
    // Jika user login tapi rolenya cuma user biasa, lempar ke halaman depan (index user)
    header("Location: ../index.php");
    
    // Hentikan eksekusi script demi keamanan
    exit;
}
?>