$(window).load(function(){   

    var member_id = $('.get-member-id').val();
    var verify_key = $('.get-verify-key').val();
    
    /** check member verify ( invite_code ) ? **/
    if (!verify_key) {

        $('#invite').modal({
            backdrop: 'static', 
            keyboard: false
        })

    } else {

        $('#invite').modal('hide');

        // Load my order inprocess 
        $.get('front/orders/' + member_id, { status: 'active' }, function(res) {
            $("#myorder_inprocess .scroll-bar-wrap").LoadingOverlay("show");
            $('#myorder_inprocess .scroll-bar-wrap').html(res);
            $("#myorder_inprocess .scroll-bar-wrap").LoadingOverlay("hide");
        })

        // get member point ( reward_point_total , balance_due-total )
        $.get('front/get_member_home/' + member_id, function(res) {
            $('.box-reward-area .bg-rewa2 .reward-point').html(numberWithCommas(res.reward_point_total));
        })
    }

});

// number format comma
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


// ================== SCRIPT MY ORDER =================================//
    //** Load my order inprocess  = event click my order tab inprocess */
    $('.my-order-area .tab-order-inprocess').on('click', function(){

        var member_id = $('.get-member-id').val();

        $("#myorder_inprocess .scroll-bar-wrap").LoadingOverlay("show");

            $.get('front/orders/' + member_id, { status: 'active' }, function(res) {
                $('#myorder_inprocess .scroll-bar-wrap').html(res);
            })

        $("#myorder_inprocess .scroll-bar-wrap").LoadingOverlay("hide");
    })

    // Load my order complete  = event click my order tab complete
    $('.my-order-area .tab-order-complete').on('click', function(){
        
        var member_id = $('.get-member-id').val();

        $("#myorder_complete .scroll-bar-wrap").LoadingOverlay("show");

            $.get('front/orders/' + member_id, { status: 'complete' }, function(res) {
                $('#myorder_complete .scroll-bar-wrap').html(res);
            })

        $("#myorder_complete .scroll-bar-wrap").LoadingOverlay("hide");
    })


    // search my order by keyword ( doc_name & patient_name ) 
    $( ".my-order-area .search-my-order" ).keyup(function() {
        var member_id = $('.get-member-id').val(); //member_id
        var my_order_active = $('.my-order-area .nav-tabs .active a').attr("href").substring(1); // get my order tab active attr.href
        var status = (my_order_active == 'myorder_inprocess') ? 'active' : 'complete'; //my order status ( inprocess, complete )
        var keyword = $(this).val(); //keyword for search

        $.get('front/orders/' + member_id, { status: status, key: keyword }, function(res) {  //find my order bind  where(member_id, status, keyword)
            $('#'+my_order_active+' .scroll-bar-wrap').html(res);
        })
    });

// ==================/. SCRIPT MY ORDER =================================//

// ================= SCRIPT REWARD =======================//
//** Get reward history */
$('.box-reward-area .reward-history').on('click', function(){

    var member_id = $(this).attr('data-id');

    $.get('front/reward/history/' + member_id, function(res) {
        $('.modal-reward-history .scroll-bar-wrap').html(res);
        $('.modal-reward-history').modal('show');
    })
})

// ================= SCRIPT REWARD =======================//
