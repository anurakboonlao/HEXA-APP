$('#submitBtn').click(function() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '/' + mm + '/' + dd;

    $('#locate').html($('#location').val());
    $('#time').html($('#pickup_time').val());
    $('#date').html(today);
});

$('#submit').click(function() {
    $( "#submit" ).prop( "disabled", true ).addClass(" btn-comple1-disabled");
    $('#formfield').submit();
});

$('.box-pickup-area #history .pickup-cancel').on('click', function() {
    var pickup_id = $(this).attr('data-id');
    var address = $('.box-pickup-area .pickup_address').attr('data-address');
    var date = $('.box-pickup-area .pickup_date').attr('data-date');
    var time = $('.box-pickup-area .pickup_time').attr('data-time');

    $('.modal-order-pickup .modal-body .clinic_name span').html(address);
    $('.modal-order-pickup .modal-body .pickup_date span').html(date);
    $('.modal-order-pickup .modal-body .pickup_time span').html(time);
    $('.modal-order-pickup .modal-body .pickup-delete').attr("href", 'front/pickup/delete/' + pickup_id);
    $('.modal-order-pickup').modal('show');
})