@extends('layout.front')
@section('content')

@php
    $setting = App\Setting::first();
	$member = App\Member::find(request()->member->id);
@endphp

@include('front.elements.navbar_header')
<div class="pd-denti1">

	<div class="container-fluid pd-denti3 mg-rewa1">
		<div class="row">
			<div class="col-lg-12 pd-denti2">
				<div class="bg-rewa3">
					<div class="font-denti1 dis-rewa pd-balance1"><h2>ใบวางบิล</h2></div>

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#process">รายการชำระ</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#complete">ประวัติการชำระเงิน</a>
						</li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div id="process" class="tab-pane pd-den-tab1 active">
							<div class="row">

								{{-- <div class="col-xl-6 col-lg-5 col-md-4 order-md-1 order-2">
									
									<label id ="checkall" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-0">
										<input type="checkbox" id="checkbox-0" class="mdl-checkbox__input">
										
										<span class="mdl-checkbox__label font-payment2">เลือกทั้งหมด</span>
									</label>
								</div> --}}

								<div class="col-xl-6 col-lg-5 col-md-4 order-md-1 order-2">
									<label id ="checkall" class="container-checkbox" for="checkbox-0">เลือกทั้งหมด
										<input type="checkbox" id="checkbox-0">
										<span class="checkmark-checkbox"></span>
									</label>
								</div>

								<div class="col-xl-6 col-lg-7 col-md-8 order-md-2 order-1">
									<div class="bg-his-pay2 font-bala1">
										<p>ยอดชำระ</p><b><span id="amount-to-payment">{{ $bill['amount_total'] }}</span></b><span>บาท</span>
										<input type="hidden" id="amount-total" value="{{ $bill['amount_total_number'] }}">
										<button class="btn-bala1" id="review-payment" type="button">ชำระเงิน</button>
									</div>
								</div>
							</div>
							<div class="mdl-card">
								<div class="mdl-card__supporting-text">
									@foreach ($bill['invoices'] as $bill)
									<div class="bg-payment1" style="padding: 10px; margin: 10px; border-radius: 15px;">
										<div>
											<input type="hidden" class="bill_number" value="{{ $bill['number'] }}">
											{{-- <label class="mdl-checkbox bg-payment2 mdl-js-checkbox mdl-js-ripple-effect check" for="checkbox-{{ $bill['number'] }}">
												<input type="checkbox" id="checkbox-{{ $bill['number'] }}" class="mdl-checkbox__input bill-id" value="{{ $bill['amount_total_origin'] }}" bill-id="{{ $bill['id'] }}">
												<div class="mdl-checkbox__label font-payment1">
													<p>เลขที่ใบวางบิล {{ $bill['number'] }}</p>
													<p>วันที่ {{ $bill['due_date'] }}</p>
													<span>{{ $bill['amount_total'] }}</span>
												</div>
											</label> --}}

											<label class="container-checkbox bg-payment3 check" for="checkbox-{{ $bill['number'] }}">
												<input type="checkbox" id="checkbox-{{ $bill['number'] }}" class="bill-id" value="{{ $bill['amount_total_origin'] }}" bill-id="{{ $bill['id'] }}">
												<span class="checkmark-checkbox" style="margin-top: 45px; margin-left: 10px;"></span>
												<div class="mdl-checkbox__label font-payment1" style="margin-left: 20px;">
													<p>เลขที่ใบวางบิล {{ $bill['number'] }}</p>
													<p>วันที่ {{ $bill['due_date'] }}</p>
													<span>{{ $bill['amount_total'] }}</span>
												</div>
											</label>
										</div>
										<button class="btn-order1 btn-his-pay1 view-bill" type="button" id="{{ $bill['id'] }}">รายละเอียด</button>
									</div>
									@endforeach

								</div>
							</div>
							<hr>
							<div class="mdl-card">
								<div class="mdl-card__supporting-text">

									<div class="row">
										<div class="col-12">
								
											<div class="panel">
												<div class="panel-body">
													<table class="table table-bordered">
														<thead>
															<th>รหัส</th>
															<th>หมายเลขใบวางบิล</th>
															{{-- <th>ผู้จ่าย</th> --}}
															<th>วันที่</th>
															<th>จำนวนเงิน</th>
															<th>รายละเอียดการชำระเงิน</th>
															<th>จำนวนที่ชำระ</th>
															<th>สถานะ</th>
															<th></th>
														</thead>
														<tbody>
															
															@foreach ($transferes as $key => $payment)
																@php
																	$bills = json_decode($payment->bill_content, true)
																@endphp
																@foreach ($bills as $bill)
																	<input type="hidden" class="payment_bill_number" value="{{ $bill['number'] }}">
																@endforeach
																@if($payment->is_success == 0)
																	<tr>
																		<td>
																			{{ ($payment->id) }}</td>
																		<td>
																			{{-- @php
																				$bills = json_decode($payment->bill_content, true)
																			@endphp --}}
																			@foreach ($bills as $bill)
																				{{-- <input type="hidden" class="payment_bill_number" value="{{ $bill['number'] }}"> --}}
																				<p>
																					<a href="bill/{{ $bill['id'] }}" target="_blank">
																						{{ $bill['number'] }} ({{ @number_format($bill['amount_total']) }})
																					</a>
																				</p>
																			@endforeach
																		</td>
																		<td>{{ set_date_format($payment->created_at) }}</td>
																		<td align="right">{{ @number_format($payment->total, 2) }}</td>
																		<td align="right">
																			{{ $payment->payment_type }} / ref : {{ $payment->ref1 }}
																		</td>
																		<td align="right">{{ @number_format($payment->total, 2) }}</td>
																		{{-- <td>{!! redemption_status($payment->is_success) !!}</td> --}}
																		<td>{!! redemption_status($payment) !!}</td>
																		<td>
																			<a href="front/transfer/home?payment_transaction_id={{ $payment->id }}" class="btn btn-warning btn-xs">
																				อัพโหลดสลิป
																			</a>
																			<a href="front/bill/delete/{{ $payment->id }}" class="btn btn-danger btn-xs" onclick="return confirm('คุณต้องการ ลบ ใช่หรือไม่')">
																				<i class="fa fa-remove"></i>
																			</a>
																		</td>
																	</tr>
																@endif
															@endforeach
														</tbody>
													</table>
												</div>
											</div>
								
											{{ $payments->appends(request()->input())->links() }}
										</div>
									</div>
					
								</div>
							</div>
						</div>
						
						<div id="complete" class="tab-pane pd-his-pay2 fade">
							<div class="row">

								@foreach ($payments as $payment)
								@php
									$bills = json_decode($payment->bill_content, true);
								@endphp
									<div class="col-xl-3 col-lg-4 col-md-6 mg-his-pay1">
										<div class="bg-his-pay1">
											<ul class="ul-his1 font-his-pay1 pd-his-pay1">
												<li><label>เลขที่ทำรายการ</label><p>{{ $payment->id }}</p><label>ช่องทางการชำระเงิน</label><p>{{ $payment->payment_type }}</p><label>วันที่ทำรายการ</label><p>{{ set_date_time_format($payment->created_at) }}</p></li>
												<li><label>ชำระสำหรับ</label><p>บริษัท เอ็กซา ซีแลม จำกัด</p>
													<div class="bt-his-pay1"><label class="text">ยอดชำระทั้งหมด</label><span> {{ number_format($payment->total, 2) }}</span></div>
												</li>
											</ul>
										</div><button class="btn-order1 btn-his-pay1" type="button" data-toggle="modal" data-target="#modal-payment-{{ $payment->id }}">รายละเอียด</button>
									</div>

									<!-- Modal In payment-->
									<div class="modal" id="modal-payment-{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered modal-payment-{{ $payment->id }}" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<ul class="ul-his1 pd-pick1 wi-his-pay1">
														<li class="font-denti1"><h3>รายละเอียดการชำระเงิน</h3></li>
														<li><div class="right-his-pay1"><span class="pd-his-pay3">ชำระเงินสำเร็จ</span><div class="check-his-pay1"><span class="icon-check input-group-addon"></span></div></div></li>
													</ul>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="hei-his-pay1">
														@foreach ($bills as $bill)
														<ul class="ul-his1 pd-pick1 text-center">
															<li>{{ $bill['number'] }}</li>
															<li><b>{{ $bill['amount_total'] }}</b></li>
														</ul>
														@endforeach
													</div>
												</div>
												<div class="modal-footer pd-his-pay4 font-his-pay2">
													<p>Total</p><span>{{ number_format($payment->total, 2) }}</span>
												</div>
											</div>
										</div>
									</div>

								@endforeach

								{!! $payments->links() !!}

							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>

	<!-- Modal bill-->
	<div class="modal" id="modal-bill" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-his1" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="wid-bill">
						<a href="" target="_blank"><button class="btn-order1 btn-bill" type="button" data-toggle="modal" data-target="#modal-process1">Download</button></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="scroll-bar-wrap">
						<div class="scroll-box scroll-bill"> 
						<iframe src="" frameborder="0" width="100%" height="700"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Preview-->
	<div class="modal" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-his1" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4>รายละเอียดบิลสำหรับการชำระเงิน</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	

</div>
@endsection
@section('script')
    <script src="{{ asset("template/front/js/my_order/script.js") }}"></script>
    <script>
        $(function(e) {

			init();

			$('#review-payment').on('click', function(e) {
				$('body').LoadingOverlay("show");
				var bills = [];
				$("input.bill-id:checked").each(function(index) {
					var bill_id = $(this).attr('bill-id');
					if (bill_id != undefined) {
						bills.push(bill_id);
					}
					console.log(bills);
				});

				$.get('front/bill/preview', {bills}, function(html) {
					$('#modal-preview .modal-body').html(html);
                    $('#modal-preview').modal('show');
					$('body').LoadingOverlay("hide");
				})
			})

            $('button.view-bill').on('click', function(e) {
                e.preventDefault();
				$('body').LoadingOverlay("show");
                var id = $(this).attr('id');
                $.get('front/bill/detail/' + id, function(res) {
                    console.log(res);
                    $('#modal-bill .modal-body iframe').attr('src', res.data.pdf_url);
                    $('#modal-bill .modal-header .wid-bill a').attr('href', res.data.pdf_url);
                    $('#modal-bill').modal('show');
					$('body').LoadingOverlay("hide");
                })
            })

			// $('.mdl-checkbox__input')[0].change(function() {
			$("#checkall input").change(function() {
                var bill_number = $(".bill_number");
				var payment_bill_number = $(".payment_bill_number");
                for(var i=0;i<bill_number.length;i++){
                    var number = $(bill_number[i]).val();
                    $("#checkbox-"+ number).prop('checked', false);

					var found_payment = false;
					for(var j=0;j<payment_bill_number.length;j++){
						if($(bill_number[i]).val() == $(payment_bill_number[j]).val()){
							found_payment = true;
						}
					}
					if(($('#checkall input').prop("checked")) && (!found_payment)) {
						$("#checkbox-"+ number).prop('checked', true);
					}
				}
                sum_amount_total();
            });

			$('.check input').change(function() {
				$("#checkbox-0").prop('checked', false);
				sum_amount_total();
			});
			
			function sum_amount_total()
			{
				var total = 0;
				var bill_number = $(".bill_number");
				for(var i=0;i<bill_number.length;i++){
					var number = $(bill_number[i]).val();
					if($("#checkbox-"+ number).prop('checked') == true){
						total += parseFloat($("#checkbox-"+ number).val());
					}
				}
	
				$('#amount-to-payment').html(thousands_separators(total));
				$('#amount-total').val(total);
			}

			function init(){
				var bill_number = $(".bill_number");
				var payment_bill_number = $(".payment_bill_number");
				for(var i=0;i<bill_number.length;i++){
					for(var j=0;j<payment_bill_number.length;j++){
						if($(bill_number[i]).val() == $(payment_bill_number[j]).val()){
							var number = $(bill_number[i]).val();
							$("#checkbox-"+ number).prop('checked', false);
							$("#checkbox-"+ number).attr("disabled", true);
							var parent = $(bill_number[i]).parent();
							var grand_parent = $(parent).parent();
							$(grand_parent).css('background-color','#ffcccc');
							$(parent).html($(parent).html() + "<div style='color:red;'>ชำระเงินแล้วรอตรวจสอบ</div>");
						}
					}
				}
				
				

				sum_amount_total();

			}
        });
    </script>
	
@endsection