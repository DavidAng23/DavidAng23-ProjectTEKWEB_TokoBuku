$(document).ready(function() {
    
    // ------------------------------------------------------------------------
    // 1. LIVE SEARCH & FILTER
    // ------------------------------------------------------------------------
    function loadBooks() {
        var keyword = $('#search-input').val();
        var category = $('#category-filter').val(); 
        
        $.ajax({
            url: 'ajax/search_book.php',
            type: 'GET',
            data: { keyword: keyword, category: category },
            success: function(responseHTML) {
                $('#book-container').html(responseHTML);
            },
            error: function(xhr, status, error) {
                console.log("Error Search: " + error);
            }
        });
    }

    $('#search-input').on('keyup', loadBooks);
    $('#category-filter').on('change', loadBooks);


    // ------------------------------------------------------------------------
    // 2. ADD TO CART (User)
    // ------------------------------------------------------------------------
    $(document).on('click', '.btn-add-to-cart', function(e) {
        e.preventDefault();
        var btn = $(this), bookId = btn.data('book-id');
        var qtyInput = $('#quantity'); 
        var quantity = qtyInput.length ? (qtyInput.val() || 1) : 1;
        var originalText = btn.html();

        // Loading
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            type: "POST", 
            url: "ajax/add_to_cart.php", 
            data: { book_id: bookId, quantity: quantity }, 
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    // Berhasil: Ganti tombol jadi hijau sebentar
                    btn.removeClass('btn-primary btn-success').addClass('btn-success').html('<i class="fas fa-check"></i> Masuk Keranjang');
                    setTimeout(() => { 
                        btn.removeClass('btn-success').addClass('btn-primary').html(originalText).prop('disabled', false); 
                    }, 1500);
                } else {
                    // Gagal: Alert biasa
                    alert(res.message); 
                    btn.html(originalText).prop('disabled', false);
                    if(res.message.includes('Login')) window.location.href = 'login.php';
                }
            },
            error: function() {
                alert('Gagal menghubungi server.');
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // ------------------------------------------------------------------------
    // 3. GLOBAL AJAX FORM (LOGIN, REGISTER, CRUD ADMIN) - BAGIAN PENTING
    // ------------------------------------------------------------------------
    $('.ajax-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var formData = new FormData(this); 
        var btn = form.find('button[type="submit"]');
        var originalBtnText = btn.html();

        // --- PERBAIKAN PENCARIAN ALERT ---
        // 1. Cari .alert-msg di DALAM form (untuk edit_buku/tambah_buku)
        var alertBox = form.find('.alert-msg');
        
        // 2. Jika tidak ketemu di dalam, cari di LUAR form (sebelah atasnya)
        // (untuk login/register/tambah_kategori)
        if (alertBox.length === 0) {
            alertBox = form.siblings('.alert-msg');
        }

        // Reset Alert (Sembunyikan dulu biar bersih)
        alertBox.hide().removeClass('alert alert-danger alert-success');

        // Tombol Loading
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            contentType: false, 
            processData: false, 
            dataType: 'json',
            success: function(res) {
                // Kembalikan tombol
                btn.prop('disabled', false).html(originalBtnText);

                if (res.success) {
                    // SUKSES: Tambah class Bootstrap alert-success dan slideDown
                    alertBox.addClass('alert alert-success').html('<i class="fas fa-check-circle me-2"></i> ' + res.message).slideDown();
                    
                    // Auto Scroll ke Alert agar user melihatnya
                    if(alertBox.length > 0) {
                        $('html, body').animate({ scrollTop: alertBox.offset().top - 100 }, 500);
                    }

                    if (res.redirect) {
                        setTimeout(function() { window.location.href = res.redirect; }, 1500);
                    } else {
                        form[0].reset();
                    }
                } else {
                    // GAGAL: Tambah class Bootstrap alert-danger dan slideDown
                    alertBox.addClass('alert alert-danger').html('<i class="fas fa-exclamation-triangle me-2"></i> ' + res.message).slideDown();
                    
                    if(alertBox.length > 0) {
                        $('html, body').animate({ scrollTop: alertBox.offset().top - 100 }, 500);
                    }
                }
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false).html(originalBtnText);
                alertBox.addClass('alert alert-danger').html('Terjadi kesalahan server: ' + status).slideDown();
            }
        });
    });

    // ------------------------------------------------------------------------
    // 4. CART ACTIONS (Tombol Plus/Minus/Hapus di Keranjang)
    // ------------------------------------------------------------------------
    $('.btn-cart-action').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var action = btn.data('action');
        var id = btn.data('id');
        var qty = btn.data('qty');
        var row = btn.closest('tr');
        
        row.css('opacity', '0.5'); // Efek loading baris

        $.ajax({
            url: 'ajax/cart_process.php',
            type: 'POST',
            data: { action: action, cart_id: id, qty: qty },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    window.location.reload(); 
                } else {
                    alert(res.message);
                    row.css('opacity', '1');
                }
            },
            error: function() {
                alert('Gagal koneksi cart.');
                row.css('opacity', '1');
            }
        });
    });

    // ------------------------------------------------------------------------
    // 5. ADMIN DELETE (Tombol Hapus Buku)
    // ------------------------------------------------------------------------
    $('.btn-delete-ajax').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');
        var url = btn.data('url'); 

        if (confirm('Yakin ingin menghapus data ini?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        btn.closest('tr').fadeOut(500, function() { $(this).remove(); });
                    } else {
                        alert('Gagal: ' + res.message);
                    }
                }
            });
        }
    });
});