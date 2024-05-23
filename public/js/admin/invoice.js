var addList = function(container) {

    var product_id = $(container + ' select[name="product_id"]').val()
    var price = $(container + ' input[name="price"]').val()
    var qty = $(container + ' input[name="qty"]').val()
    var discount_percent = $(container + ' input[name="discount_percent"]').val()
    
    if (price == "") {
        
        $(container + ' input[name="price"]').focus()
        
        return false
    }

    if (qty == "") {
        
        $(container + ' input[name="qty"]').focus()

        return false
    }
    
    var data = {
        method: 'get',
        product_id: product_id,
        price: price,
        qty: qty,
        discount_percent: discount_percent
    }

    $.get('admin/invoice/create_list_item/create', data, function(html) {
        $.LoadingOverlay("show")
        $('tbody#list-item').append(html)

        $.LoadingOverlay("hide")

        removeList();

        $(container + ' input[name="price"]').val("")
        $(container + ' input[name="qty"]').val("")
    
    })

}

var addService = function(container) {

    var description = $(container + ' input[name="description"]').val()
    var price = $(container + ' input[name="price"]').val()
    var qty = $(container + ' input[name="qty"]').val()
    
    if (description == "") {
        
        $(container + ' input[name="description"]').focus()
        
        return false
    }
    
    if (price == "") {
        
        $(container + ' input[name="price"]').focus()
        
        return false
    }

    if (qty == "") {
        
        $(container + ' input[name="qty"]').focus()

        return false
    }
    
    var data = {
        method: 'get',
        description: description,
        price: price,
        qty: qty
    }

    $.get('admin/invoice/create_list_item/create?type=s', data, function(html) {
        $.LoadingOverlay("show")
        $('tbody#service-item').append(html)

        $.LoadingOverlay("hide")

        removeList();

        $(container + ' input[name="description"]').val("")
        $(container + ' input[name="price"]').val("")
        $(container + ' input[name="qty"]').val("")
    
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

var sortTr = function(tbodyId) {
    $('#' + tbodyId).sortable();
}

$(function(e) {

    sortTr("list-item")
    sortTr("service-item")

    removeList();

    $("#form-add-list").on('click', '.btn-primary', function(e) {
        e.preventDefault();
        
        addList("#form-add-list")
        sortTr("list-item")
    })

    $("#form-add-service").on('click', '.btn-primary', function(e) {
        e.preventDefault();
        
        addService("#form-add-service")
        sortTr("service-item")

    })

    $('select[name="invoice_type"]').on('change', function(e) {
        e.preventDefault();

        var type = $(this).val();

        window.location = "admin/invoice/create?invoice_type=" + type;
    })

})