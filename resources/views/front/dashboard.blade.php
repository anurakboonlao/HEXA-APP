@extends('layout.front')
@section('content')

@php
	$member = App\Member::find(request()->member->id);
@endphp

@include('front.elements.navbar_header')

<!--popup-->
<div class="modal" id="profile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content radi-popup1">
			<div class="modal-header line-popup2">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body pd-popup7">
				<div class="col-12">
					<div class="row">
						<div class="col-4">
							<form>
								<h4 class="text-center">Username</h4>
								<button class="btn-popup1 bg-blue text-white" type="button" data-toggle="modal" data-target="#modal-exchange1">แก้ไขโปรไฟล์</button>
								<button type="button" class="btn-popup1" data-toggle="modal" data-target="#inviteSuccessfully">Change Password</button>
							</form>
						</div>
						<div class="col-8">
							<form>
								<input type="text" placeholder="กรุณากรอก Invite Code" class="form-modal1 text-center">
								<button type="button" class="btn-popup1" data-toggle="modal" data-target="#inviteSuccessfully">ขอรับ invite Code เพื่อยืนยันตัวตน</button>
								<button class="btn-rewa4 btn-suc1" type="button" data-toggle="modal" data-target="#modal-exchange1">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="pd-denti1">
    <div class="container-fluid pd-denti3">
        <div class="mg-denti1">
            <div class="row">
                <div class="col-md-8 pd-denti2">
                    <!-- import promotion slide -->
                    @include('front.elements.promotion_slide')
                    <!-- /.port promotion slide -->
                </div>
                <div class="col-md-4 pd-denti2">
                <div class="row">
                    @if($member->type == 'doctor')
                    <div class="col-md-6">
                        {{ Form::open(['files' => true, 'url' => 'front/member/loginExternal', 'class' => 'mg-profile1', 'method' => 'POST']) }}
                            <figure class="snip1584 font-clinic1">
                                    <img src="template/front/images/dentist/img-dent5-online-ordering.png" alt="" width="100%">
                                    <figcaption>
                                        <p>
                                        <img src="template/front/images/dentist/icon_ordering.svg" height="32px"> Online Ordering
                                        </p>
                                    </figcaption><a href="javascript: submitToOnlineOrder()"></a>
                                    <div class="col-sm-6 col-xs-12 pull-left" style="display:none;">
                                        <button id="btn-online-order" type="submit" class="btn-pass1;" ></button>
                                    </div>
                            </figure>

						{{ Form::close() }}
                    </div>
                    <div class="col-md-6">
                        <figure class="snip1584 font-clinic1">
                            <img src="template/front/images/dentist/img-dent4-pricelist-new.png" alt="" width="100%">
                            <figcaption>
                                <p class="white-color">Pricelist</p>
                            </figcaption>
                            <a href="files/settings-{{ $setting->hexa_price_list }}" target="_blank"></a>
                        </figure>
                    </div>
                   @else
                        <div class="col-md-6">
                        {{ Form::open(['files' => true, 'url' => 'front/member/loginExternal', 'class' => 'mg-profile1', 'method' => 'POST']) }}
                            <figure class="snip1584 font-clinic1">
                                    <img src="template/front/images/clinic/img-clinic-new01.png" alt="" width="100%">
                                    <figcaption>
                                        <p>
                                        <img src="template/front/images/clinic/icon-clinic1.png" height="42px"> Online Ordering
                                        </p>
                                    </figcaption><a href="javascript: submitToOnlineOrder()"></a>
                                    <div class="col-sm-6 col-xs-12 pull-left" style="display:none;">
                                        <button id="btn-online-order" type="submit" class="btn-pass1;" ></button>
                                    </div>
                            </figure>

						{{ Form::close() }}
                        </div>
                        <div class="col-md-6">
                            <figure class="snip1584 font-clinic1">
                                <img src="template/front/images/clinic/img-clinic-new02.png" alt="" width="100%">
                                <figcaption>
                                    <p><img src="template/front/images/clinic/icon-clinic1.png" height="42px">&nbsp;ตรวจสอบและชำระค่าแลป</p>
                                </figcaption><a href="front/bill/home"></a>
                            </figure>
                        </div>
                   @endif
                </div>

                   <div class="row px-3">
                        <figure class="snip1584">
                            <img src="template/front/images/dentist/img-denti3.png" alt="" width="100%">
                            <figcaption class="font-price1">
                                <p>Dental Supplies<span>สั่งง่าย ได้ไว ไม่มีขั้นต่ำ</span></p>
                            </figcaption>
                            <a href="{{ $setting->dental_supply_link }}" target="_blank"></a>
                        </figure>
                   </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-12 pd-denti2 my-order-area">
                <div class="bg-denti1">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="pd-denti4 font-denti1">
                                <h2>My Orders</h2>&nbsp;&nbsp;<p>ตรวจสอบสถานะงานได้ทุกเคส ทุกขั้นตอน</p>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="pd-denti4">
                                <input type="text" class="main-input main-name search-my-order" name="key" placeholder="คลินิกหรือคนไข้" onfocus="clearText(this)" onblur="replaceText(this)">
                            </div>
                        </div>
                    </div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item tab-order-inprocess active">
                            <a class="nav-link active" data-toggle="tab" href="#myorder_inprocess">In Process</a>
                        </li>
                        <li class="nav-item tab-order-complete">
                            <a class="nav-link" data-toggle="tab" href="#myorder_complete">Complete</a>
                        </li>
                    </ul>
                    <!-- Tab panes My Order -->
                    <div class="tab-content">
                        <!-- Myorder In Process -->
                        <div id="myorder_inprocess" class="tab-pane pd-den-tab1 active">
                            <div class="scroll-bar-wrap">
                                <!-- ajax get my order inprocess append -->
                            </div>
                        </div>
                        <!-- /.Myorder In Process -->
                        <!-- Myorder Complete -->
                        <div id="myorder_complete" class="tab-pane pd-den-tab1 fade">
                            <div class="scroll-bar-wrap">
                                <!-- ajax get my order inprocess append -->
                            </div>
                        </div>
                        <!-- Myorder Complete -->
                    </div>
                </div>
            </div>

            @if($member->type == 'doctor')
                <!-- Hexa Reward / type = doctor --->
                <div class="col-xl-4 col-lg-12 pd-denti2 box-reward-area">
                    <div class="bg-denti1 bg-rewa1">
                        <div class="font-denti1 dis-rewa line-rewa1">
                            <h2>Hexa Reward</h2>
                            <p>ตรวจสอบคะแนนและแลกของรางวัลได้ตามใจ</p>
                        </div>
                        <div class="pd-rewa1">
                            <div class="row">
                                <div class="col-lg-4 col-5 pd-rewa3">
                                    <button class="btn-rewa1 reward-history" type="button" data-id="{{ $member->id }}">ประวัติการแลกคะแนน</button>
                                    <a href="front/reward?modal=cart">
                                        <button class="btn-rewa1" id="voucher_cart" data-id="{{ $member->id }}">ตะกร้าของรางวัล</button>
                                    </a>
                                </div>
                                <div class="col-lg-8 col-7 pd-rewa2">
                                    <div class="bg-rewa2">
                                        <p><span class="reward-point">0,000</span> <span>คะแนน</span></p>
                                    </div>
                                    <a href="front/reward"><button class="btn-rewa1 btn-rewa2">แลกคะแนน</button></a>
                                </div>
                            </div>
                        </div>
                        <img src="template/front/images/dentist/img-reward1.png" class="wid-rewa1">
                    </div>
                </div>
                <!-- /.Hexa Reward / type = doctor --->
            @else
                <!-- Order Pickup / type = clinic --->
                <div class="col-xl-4 col-lg-12 pd-denti2 box-pickup-area">
                    <div class="bg-denti1">
                        <div class="font-denti1 pd-pick2">
                            <h2>Order Pick up</h2>
                            <p>เรียกรับงานได้ทันที</p>
                        </div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#job">รับงาน</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#history">ประวัติการเรียกงาน</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="job" class="tab-pane pd-den-tab1 active">
                                @include('front.pickup.index')
                            </div>
                            <div id="history" class="tab-pane pd-den-tab1 fade">
                                <div class="scroll-bar-wrap">
                                    <!-- ajax get order pickup append -->
                                    @include('front.pickup.get_history')
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.Order Pickup / type = clinic --->
            @endif
        </div>
    </div>
    <!-- import modal popup -->
    @include('front.elements.modal_popup')
    <!-- /.import modal popup -->
</div>

<!-- Modal User have not filled notification email and phone-->
<div class="modal" id="modal-go-to-profile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 font-thank1">
                <p>กรุณากรอกข้อมูลอีเมลกับหมายเลขโทรศัพท์ใน profile <br> เพื่อรับ notification </p>
                <button class="btn-rewa4 btn-suc1 mb-3" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ asset("template/front/js/my_order/script.js") }}"></script>
    <script src="{{ asset("template/front/js/pickup/script.js") }}"></script>
    <script src="{{ asset("template/front/js/reward/script.js") }}"></script>
    <script>
        function submitToOnlineOrder() {
            var emailNoti = "<?php Print($member->emailNoti); ?>";
            var PhoneNoti = "<?php Print($member->phoneNoti); ?>";
            if(!emailNoti || !PhoneNoti){
                $('#modal-go-to-profile').modal('show');
            }            
            else {
                document.getElementById('btn-online-order').click();
            }
        }
    </script>
@endsection
