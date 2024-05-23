$(window).load(function(){   

    var member_id = $('.get-member-id').val();
    var verify_key = $('.get-verify-key').val();
    var url = new URL(window.location);
    var modal = product = url.searchParams.get('modal') // check url parameter?modal=cart ( true = show modal class='modal-reward-cart' )
    
    /** check member verify ( invite_code ) ? **/
    if (!verify_key) {

        $('#invite').modal({
            backdrop: 'static', 
            keyboard: false
        })

    } else {

        $('#invite').modal('hide');

        // get member point ( reward_point_total , balance_due-total )
        getRewardPoint(member_id)

        // check parameter?modal=cart ( true = show modal class='modal-reward-cart' )
        if (modal == 'cart') {

            //get voucher cart 
            $.get('front/reward/get_voucher_cart', { member_id: member_id }, function(res) {
                $('.modal-reward-cart .modal-body').html(res);
                $('.modal-reward-cart').modal('show');
                $(".modal-reward-cart .btn-rewa4").prop('disabled', true);
                $(".modal-reward-cart .reward-new-address").prop('disabled', true);
            })
            
            //get erp doctor address
            $.get('front/reward/get_doctor_address', { member_id: member_id }, function(res) {
                $(".modal-reward-cart .modal-body .address").LoadingOverlay("show");
                $('.modal-reward-cart .modal-body .address').html(res);
                $(".modal-reward-cart .modal-body .address").LoadingOverlay("hide");

                $(".modal-reward-cart .reward-new-address").prop('disabled', false);
            })
        }
    }
});

// function get member point ( reward_point_total , balance_due-total )
function getRewardPoint(member_id){
    $.get('front/get_member_home/' + member_id, function(res) {
        $('.reward-area .reward-point-total').html((res.reward_point_total <= 0 ) ? '0' : numberWithCommas(res.reward_point_total));
        $('.reward-area .txt-reward-point').val((res.reward_point_total <= 0 ) ? '0' : numberWithCommas(res.reward_point_total));
    })
}

// number format comma
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// get voucher detail append to modal reward detail
$('.reward-area .reward-detail').on('click', function(){

    var member_id = $('.get-member-id').val();
    var voucher_id = $(this).attr('data-id');

    $.get('front/reward/detail/' + voucher_id, { member_id: member_id }, function(res) {
        
        if (res.status == false) {

            swal({
                type: "error",
                title: "ขออภัย คะแนนสะสมไม่เพียงพอ",
                showConfirmButton: true,
                timer: false
            })

        } else {

            $('.modal-reward-detail .modal-content').html(res);
            $('.modal-reward-detail').modal('show');

        }
    })
})

function checkBox() {
    
    var checkbox_count = $(".modal-reward-cart [type='checkbox']:checked").length;
    
    if (checkbox_count > 0) {

        $(".modal-reward-cart .btn-rewa4").prop('disabled', false);

    } else {

        $(".modal-reward-cart .btn-rewa4").prop('disabled', true);
    }
}

//reward check all list
$(".modal-reward-cart #checkAll").click(function () {
    var reward_point_total = $('#doc_reward_total').attr('data-value').replace(/,/g, ''); 
    var status = $(this).is(':checked');
    var type = $('.voucher_cart_area').attr('data-type');

    $(".check input[type=checkbox]").prop('checked', $(this).prop('checked'));
    
    if (status == true) {

        $('.modal-reward-cart .check').addClass('is-checked');

        if (type == 'voucher_cart') {
            sumTotalVoucher(reward_point_total);
        } else {
            sumTotalVoucher2(reward_point_total);
        }

    } else {

        $('.modal-reward-cart .check').removeClass('is-checked');

        if (type == 'voucher_cart') {
            sumTotalVoucher(reward_point_total);
        } else {
            sumTotalVoucher2(reward_point_total);
        }
    }
    checkBox();
});

//reward check 1 more
$(".modal-reward-cart .reward-list .check input").click(function () {
    var reward_point_total = $('#doc_reward_total').attr('data-value').replace(/,/g, ''); 
    var sort = $(this).attr('data-id');
    var status = $(this).is(':checked');
    var type = $('.voucher_cart_area').attr('data-type');

    if (status == true) {

        $('.modal-reward-cart .reward-'+ sort).addClass('is-checked');
        
        if (type == 'voucher_cart') {
            sumTotalVoucher(reward_point_total);
        } else {
            sumTotalVoucher2(reward_point_total);
        }

    } else {

        $('.modal-reward-cart .reward-'+ sort).removeClass('is-checked');

        if (type == 'voucher_cart') {
            sumTotalVoucher(reward_point_total);
        } else {
            sumTotalVoucher2(reward_point_total);
        }
    }
    checkBox();
});

//sum point and voucher_value ( voucher_cart )
function sumTotalVoucher(reward_point_total) {

    var input = document.getElementsByName("voucher_cart_id[]");
    var amount = 0;
    var point = 0;
    for (var i = 0; i < input.length; i++) {
        if (input[i].checked) {
            amount += parseFloat(input[i].getAttribute('amount'));
            point += parseFloat(input[i].getAttribute('point'));
        }
    }

    if (point > reward_point_total) {

        $('.modal-reward-cart input[name=point]').addClass('boder-reward-point-limit');
        $('.modal-reward-cart input[name=amount]').addClass('boder-reward-point-limit');
        $('.modal-reward-cart .alert-point-limit').removeClass('hide');
        $('.modal-reward-cart .btn-rewa4').attr("disabled", true);
        $('.modal-reward-cart .btn-rewa4').addClass('btn-reward-point-limit');

    }else{

        $('.modal-reward-cart input[name=point]').removeClass('boder-reward-point-limit');
        $('.modal-reward-cart input[name=amount]').removeClass('boder-reward-point-limit');
        $('.modal-reward-cart .alert-point-limit').addClass('hide');
        $('.modal-reward-cart .btn-rewa4').removeClass('btn-reward-point-limit');
        
    }

    $('.modal-reward-cart input[name=amount]').val(amount);
    $('.modal-reward-cart input[name=point]').val(point);
}

//sum point and voucher_value ( exchange reward 1 more )
function sumTotalVoucher2(reward_point_total) {

    var input = document.getElementsByName("voucher_id");
    var amount = 0;
    var point = 0;
    for (var i = 0; i < input.length; i++) {
        if (input[i].checked) {
            amount += parseFloat(input[i].getAttribute('amount'));
            point += parseFloat(input[i].getAttribute('point'));
        }
    }

    if (point > reward_point_total) {

        $('.modal-reward-cart input[name=point]').addClass('boder-reward-point-limit');
        $('.modal-reward-cart input[name=amount]').addClass('boder-reward-point-limit');
        $('.modal-reward-cart .alert-point-limit').removeClass('hide');
        $('.modal-reward-cart .btn-rewa4').attr("disabled", true);
        $('.modal-reward-cart .btn-rewa4').addClass('btn-reward-point-limit');

    }else{

        $('.modal-reward-cart input[name=point]').removeClass('boder-reward-point-limit');
        $('.modal-reward-cart input[name=amount]').removeClass('boder-reward-point-limit');
        $('.modal-reward-cart .alert-point-limit').addClass('hide');
        $('.modal-reward-cart .btn-rewa4').removeClass('btn-reward-point-limit');
        
    }

    $('.modal-reward-cart input[name=amount]').val(amount);
    $('.modal-reward-cart input[name=point]').val(point);
}

// Remove Voucher Cart List 1 more
$('.modal-reward-cart .reward-delete').on('click', function(){
    var voucher_cart_id = $(this).attr('data-id');
    $.get( "front/reward/remove_voucher/" + voucher_cart_id, function( res ) {
        $('.modal-reward-cart .reward-list .item-list-' + voucher_cart_id).closest('li').remove();
    }); 
})

// Append html new address reward
$('.modal-reward-cart .reward-new-address').on('click', function(){
    var element = 
        '<div class="address remove-scroll">' +
            '<div class="font-excha1 font-add1">' +
                '<h4>ที่อยู่จัดส่งใหม่</h4>' +
                '<label>ชื่อ-สกุล</label>' +
                '<input type="text" name="name" placeholder="กรุณาใส่ ชื่อ-สกุล" class="form-add1" required="">' +
                '<label>ชื่อคลินิก/โรงพยาบาล</label>' +
                '<input type="text" name="clinic" placeholder="กรุณาใส่ ชื่อคลินิก/โรงพยาบาล" class="form-add1" required="">' +
                '<label>ที่อยู่</label>' +
                '<textarea name="address" cols="30" class="form-add1" rows="1" placeholder="กรุณาใส่ ที่อยู่" required=""></textarea>' +
                '<label>หมายเลขโทรศัพท์</label>' +
                '<input type="number" name="phone" placeholder="กรุณาใส่ หมายเลขโทรศัพท์" class="form-add1" min="0" required="">' +
            '</div>'
        '</div>'

    $('.modal-reward-cart .left-area').html(element);
    $('.modal-reward-cart .line-excha1').addClass('mt-4');
})