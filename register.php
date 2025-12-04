<?php
include_once 'template/header.php';
// Redirect jika sudah login
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm mt-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrasi</h4>
            </div>
            <div class="card-body">
                
                <div class="alert-msg"></div>

                <form action="ajax/auth.php" method="POST" class="ajax-form">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>

            </div>
            <div class="card-footer text-center">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'template/footer.php'; ?>