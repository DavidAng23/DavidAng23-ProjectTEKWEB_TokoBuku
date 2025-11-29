$(document).ready(function() {
    $(document).on('change blur', '#quantity', function() {
        var val = parseInt($(this).val()), max = parseInt($(this).attr('max'));
        if (isNaN(val) || val < 1) $(this).val(1);
        else if (val > max) { alert('Maksimal ' + max); $(this).val(max); }
    });

    $('.btn-add-to-cart').on('click', function(e) {
        e.preventDefault();
        var btn = $(this), bookId = btn.data('book-id');
        var qtyInput = btn.closest('.d-flex').find('input[type="number"]');
        var quantity = qtyInput.length ? (qtyInput.val() || 1) : 1;

        var card = btn.closest('.card'), img = card.find('img').eq(0);
        if (!img.length) img = $('.col-md-5 img').eq(0);
        
        if (img.length) {
            var clone = img.clone().offset({top: img.offset().top, left: img.offset().left})
                .css({'opacity':'0.8', 'position':'absolute', 'height':img.height(), 'width':img.width(), 'z-index':'100', 'object-fit':'cover', 'border-radius':'10px'})
                .appendTo('body').animate({
                    'top': btn.offset().top, 'left': btn.offset().left, 'width':'10px', 'height':'10px', 'opacity':'0.1'
                }, 500, function() { $(this).remove(); });
        }

        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            type: "POST", url: "ajax/add_to_cart.php",
            data: { book_id: bookId, quantity: quantity }, dataType: "json",
            success: function(res) {
                if (res.success) {
                    btn.removeClass('btn-primary btn-success').addClass('btn-success').html('<i class="fas fa-check"></i>');
                    setTimeout(() => { btn.removeClass('btn-success').addClass('btn-primary').html(originalText).prop('disabled', false); }, 1500);
                } else {
                    alert(res.message); btn.html(originalText).prop('disabled', false);
                }
            }
        });
    });
});