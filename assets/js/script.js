$(document).ready(function() {
    
    // --- 1. LIVE SEARCH & FILTER (GABUNGAN) ---
    function loadBooks() {
        var keyword = $('#search-input').val();
        var category = $('#category-filter').val(); // Ambil nilai dropdown
        
        $.ajax({
            url: 'ajax/search_book.php',
            type: 'GET',
            data: { 
                keyword: keyword,
                category: category 
            },
            success: function(responseHTML) {
                $('#book-container').html(responseHTML);
            },
            error: function(xhr, status, error) {
                console.log("Error Search: " + error);
            }
        });
    }

    // Panggil fungsi saat ngetik ATAU ganti dropdown
    $('#search-input').on('keyup', loadBooks);
    $('#category-filter').on('change', loadBooks);


    // --- 2. ADD TO CART ---
    $(document).on('click', '.btn-add-to-cart', function(e) {
        e.preventDefault();
        var btn = $(this), bookId = btn.data('book-id');
        var qtyInput = $('#quantity'); 
        var quantity = qtyInput.length ? (qtyInput.val() || 1) : 1;
        var originalText = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            type: "POST", 
            url: "ajax/add_to_cart.php", 
            data: { book_id: bookId, quantity: quantity }, 
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    btn.removeClass('btn-primary btn-success').addClass('btn-success').html('<i class="fas fa-check"></i> Masuk Keranjang');
                    setTimeout(() => { 
                        btn.removeClass('btn-success').addClass('btn-primary').html(originalText).prop('disabled', false); 
                    }, 1500);
                } else {
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

    // --- 3. GLOBAL AJAX FORM ---
    $('.ajax-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var formData = new FormData(this); 
        var btn = form.find('button[type="submit"]');
        var originalBtnText = btn.html();
        var alertBox = form.find('.alert-msg');

        alertBox.html('').removeClass('alert alert-danger alert-success d-none');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            contentType: false, 
            processData: false, 
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alertBox.addClass('alert alert-success').html(res.message);
                    if (res.redirect) {
                        setTimeout(function() { window.location.href = res.redirect; }, 1000);
                    } else {
                        form[0].reset();
                        btn.prop('disabled', false).html(originalBtnText);
                    }
                } else {
                    alertBox.addClass('alert alert-danger').html(res.message);
                    btn.prop('disabled', false).html(originalBtnText);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alertBox.addClass('alert alert-danger').html('Terjadi kesalahan server.');
                btn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // --- 4. CART ACTIONS ---
    $('.btn-cart-action').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var action = btn.data('action');
        var id = btn.data('id');
        var qty = btn.data('qty');
        var row = btn.closest('tr');
        row.css('opacity', '0.5');

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

    // --- 5. ADMIN DELETE ---
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