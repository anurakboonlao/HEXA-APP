$('.modal-reward-detail .input-amount').bind("change paste keyup", function() {
    var amount = Number($(this).val());
    var reward_point_total = Number($('.txt-reward-point').val().replace(/,/g, '')); 
    var exchange_rate = Number($('.modal-reward-detail .input-exchange-rate').val());
    var sum_point = (amount * exchange_rate);
    var voucher_limit = Number($(this).attr('min'));

    var checkFloatAmount = amount/100;

    if (isFloat(checkFloatAmount) == true) { // check reward amount float == true

        if (amount < voucher_limit) {
            $('.modal-reward-detail .alert-point-limit').removeClass('hide');
            $('.modal-reward-detail .alert-point-limit').html('*ขออภัย มูลค่าของรางวัลที่แลกได้คือ '+ voucher_limit + ' บาท ขึ้นไป');
        } else {
            $('.modal-reward-detail .alert-point-limit').removeClass('hide');
            $('.modal-reward-detail .alert-point-limit').html('*ขออภัย ต้องกรอกมูลค่าของรางวัลเป็นจำนวนเต็ม <br> เช่น 100, 200');
        }

        $('.modal-reward-detail .btn-rewa4').attr("disabled", true);
        $('.modal-reward-detail .btn-rewa4').addClass('btn-reward-point-limit');

    } else {  // check reward amount float == false

        $('.modal-reward-detail .input-point').val(numberWithCommas(sum_point));

        if (sum_point > reward_point_total || amount < voucher_limit) {

            $('.modal-reward-detail .input-point').addClass('boder-reward-point-limit');
            $('.modal-reward-detail .input-amount').addClass('boder-reward-point-limit');
            
            if (amount < voucher_limit) {
                $('.modal-reward-detail .alert-point-limit').removeClass('hide');
                $('.modal-reward-detail .alert-point-limit').html('*ขออภัย มูลค่าของรางวัลที่แลกได้คือ '+ voucher_limit + ' บาท ขึ้นไป');

            } else {

                $('.modal-reward-detail .alert-point-limit').removeClass('hide');
                $('.modal-reward-detail .alert-point-limit').html('*ขออภัยค่ะ คะแนนไม่เพียงพอสำหรับแลกของรางวัล');
            }

            $('.modal-reward-detail .btn-rewa4').attr("disabled", true);
            $('.modal-reward-detail .btn-rewa4').addClass('btn-reward-point-limit');

        }else{

            $('.modal-reward-detail .input-point').removeClass('boder-reward-point-limit');
            $('.modal-reward-detail .input-amount').removeClass('boder-reward-point-limit');

            if (amount < voucher_limit) {

                $('.modal-reward-detail .alert-point-limit').addClass('hide');

            } else {

                $('.modal-reward-detail .alert-point-limit').addClass('hide');
            }

            $('.modal-reward-detail .btn-rewa4').attr("disabled", false);
            $('.modal-reward-detail .btn-rewa4').removeClass('btn-reward-point-limit');
        }
    }
});

// Add Reward 1 more to modal-reward-sigle
$('.modal-reward-detail .reward-ex').on('click', function(){
    var amount = $('.modal-reward-detail .input-amount').val();
    var point = $('.modal-reward-detail .input-point').val();
    var voucher_id = $('.modal-reward-detail input[name=voucher_id]').val();
    var voucher_name = $('.modal-reward-detail input[name=voucher_name]').val();
    var member_id = $('.get-member-id').val();

    var checkFloatAmount = amount/100;

    if (isFloat(checkFloatAmount) == true) {

        if (amount < voucher_limit) {
            $('.modal-reward-detail .alert-point-limit').removeClass('hide');
            $('.modal-reward-detail .alert-point-limit').html('*ขออภัย มูลค่าของรางวัลที่แลกได้คือ '+ voucher_limit + ' บาท ขึ้นไป');
        } else {
            $('.modal-reward-detail .alert-point-limit').removeClass('hide');
            $('.modal-reward-detail .alert-point-limit').html('*ขออภัย ต้องกรอกมูลค่าของรางวัลเป็นจำนวนเต็ม <br> เช่น 100, 200');
        }

        $('.modal-reward-detail .btn-rewa4').attr("disabled", true);
        $('.modal-reward-detail .btn-rewa4').addClass('btn-reward-point-limit');

    } else {

        if (amount == "" || amount == 0) {
            $('.modal-reward-detail .input-point').addClass('boder-reward-point-limit');
            $('.modal-reward-detail .input-amount').addClass('boder-reward-point-limit');
            $('.modal-reward-detail .btn-rewa4').attr("disabled", true);
            $('.modal-reward-detail .btn-rewa4').removeClass('btn-reward-point-limit');
            
            return false;

        }else{

            $('.modal-reward-detail .input-point').removeClass('boder-reward-point-limit');
            $('.modal-reward-detail .input-amount').removeClass('boder-reward-point-limit');
            $('.modal-reward-detail .btn-rewa4').attr("disabled", false);
            $('.modal-reward-detail .btn-rewa4').removeClass('btn-reward-point-limit');

            $.get('front/reward/get_reward_to_modal', { amount: amount, point: point, voucher_id: voucher_id, voucher_name: voucher_name, member_id: member_id, }, function(res) {
                $('.modal-reward-cart .modal-body').html(res);
                $(".modal-reward-cart .mg-excha1 input").val("");
                $('.modal-reward-cart').modal('show');
                $(".modal-reward-cart .btn-rewa4").prop('disabled', true);

                $(".modal-reward-cart .reward-new-address").prop('disabled', true);
            });

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

// number format comma
function numberWithCommas(x) {
    return x.toString().replace(/,/g, '');
}

// check float reward point
function isFloat(value){
    return Number(value) === value && value % 1 !== 0;
}