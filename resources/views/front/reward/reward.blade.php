@extends('layout.front')
@section('content')

@php
	$member = App\Member::find(request()->member->id);
@endphp

@include('front.elements.navbar_header')

<div class="pd-denti1 reward-area">
    <div class="container-fluid pd-denti3 mg-rewa1">
        <div class="row">
            <div class="col-lg-12 pd-denti2">
                <div class="bg-rewa3">
                    <div class="font-denti1 dis-rewa line-rewa2">
                        <h2>Hexa Reward</h2>
                        <p>ตรวจสอบคะแนนและแลกของรางวัลได้ตามใจ</p>
                    </div>
                    <div class="row">
                        <div class="col-xl-7 col-md-6">
                            @php
                                $voucher_cart = App\VoucherCart::where('member_id', $member->id)->count();
                            @endphp
                            @if($voucher_cart)
                                <div class="bell-rewa1"><span class="icon-bell-o input-group-addon"></span></div>
                            @endif
                            <button class="btn-rewa1 btn-rewa5" id="voucher_cart" data-id="{{ $member->id }}">ตะกร้าของรางวัล</button>
                        </div>
                        <div class="col-xl-5 col-md-6">
                            <div class="bg-rewa4 font-rewa1">
                                <font class="dis-rewa1">Hexa Reward Points</font><b class="reward-point-total">0,000</b><span>คะแนน</span>
                                <p class="co">(คำนวณจากยอดสั่งซื้อตั้งแต่ 1 ม.ค. 2564 - ปัจจุบัน)</p>
                                <input type="hidden" class="txt-reward-point" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="scroll-bar-wrap">
                        <div class="scroll-box pd-rewa4 scro-rewa1">	
                            <div class="row">
                                @foreach($vouchers as $voucher)
                                <div class="col-xl-3 col-md-4 pd-rewa5">
                                    <div class="reward-list-box">
                                        <img src="{{ store_image($voucher->image) }}" width="100%"> 
                                    </div>
                                    <div class="line-rewa3 hei-rewa1">
                                        <p class="point2">{{ $voucher->desctiption }}</p>
                                        <p class="point2">{{ $voucher->voucher_condition }}</p>
                                        <button class="btn-order1 btn-rewa3 reward-detail" type="button" data-id="{{ $voucher->id }}">รายละเอียด</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- import modal popup -->
    @include('front.elements.modal_popup')
    <!-- /.import modal popup -->
</div>
@endsection
@section('script')

    <script src="{{ asset("template/front/js/reward/script.js") }}"></script>
    <script>
        $('.reward-area #voucher_cart').on('click', function(){
            var member_id = $(this).attr('data-id');

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
        })
    </script>
@endsection