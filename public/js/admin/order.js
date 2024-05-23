var addList = function(container) {

    var product_id = $(container + ' select[name="product_id"]').val()
    var price_code = $(container + ' input[name="price_code"]').val()
    var qty = $(container + ' input[name="qty"]').val()
    var note = $(container + ' input[name="note"]').val()
    
    if (price_code == "") {
        
        $(container + ' input[name="price_code"]').focus()
        
        return false
    }

    if (qty == "") {
        
        $(container + ' input[name="qty"]').focus()

        return false
    }
    
    var data = {
        method: 'get',
        product_id: product_id,
        price_code: price_code,
        qty: qty,
        note: note
    }

    $.get('admin/order/create_list_item/create', data, function(html) {
        $.LoadingOverlay("show")
        $('tbody#list-item').append(html)

        $.LoadingOverlay("hide")

        removeList();

        $(container + ' input[name="price_code"]').val("")
        $(container + ' input[name="qty"]').val("")
        $(container + ' input[name="note"]').val("")
    
    })

}

var removeList = function() {

    $('a.remove-item').on('click', function(e) {

        e.preventDefault()

        var confirm = "Confirm !.";
        if (confirm) {

            $(this).closest("tr").remove();
        }

    })
}

$(function(e) {

    removeList();

    $("#form-add-list").on('click', '.btn-primary', function(e) {
        e.preventDefault();
        
        addList("#form-add-list")
    })
})