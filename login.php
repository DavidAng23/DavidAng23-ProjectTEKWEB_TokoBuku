<?php
require_once 'classes/Auth.php';
include_once 'template/header.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin/index.php' : 'index.php'));
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow mt-5">
            <div class="card-header bg-success text-white text-center">
                <h4>Login Pengguna</h4>
            </div>
            <div class="card-body">
                
                <div class="alert-msg"></div>
                
                <form action="ajax/auth.php" method="POST" class="ajax-form">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Masuk</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'template/footer.php'; ?>