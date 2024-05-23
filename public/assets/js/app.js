$('.view-product-modal').on('click', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    $.get('product/modal/' + id, function(html) {
        $('#modal-view-product .modal-content').html(html);
        $('#modal-view-product').modal('show');
    })
})