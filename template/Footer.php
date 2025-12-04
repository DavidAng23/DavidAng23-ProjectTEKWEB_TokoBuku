<?php
// LOGIKA PATH OTOMATIS JS
// Cek apakah script.js ada di folder saat ini?
// Jika tidak, cek di folder atasnya (parent directory)
$path_js = 'assets/js/script.js';
if (!file_exists($path_js) && file_exists('../' . $path_js)) {
    $path_js = '../' . $path_js;
}
?>
    </main> 
    <footer class="text-center text-muted mt-5 mb-3">
        <p>&copy; 2025 - Project Kelompok 4</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="<?php echo $path_js; ?>"></script>
  </body>
</html>