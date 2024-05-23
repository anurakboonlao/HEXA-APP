@extends('layout.front')
@section('content')

@php
    $setting = App\Setting::first();
	$member = App\Member::find($payment->member_id);
@endphp

@include('front.elements.navbar_header')

@php
    $bills = json_decode($payment->bill_content, true);
    $paymentRef = json_decode($payment->payment_return, true)
@endphp

<div class="pd-denti1">
<div class="container-fluid pd-denti3 mg-rewa1">
	<div class="row">
		<div class="col-lg-12 pd-denti2">
			<div class="bg-rewa3">
                <br>
				<div class="font-denti1 dis-rewa pd-balance1"><h2>ยืนยันการชำระเงิน</h2></div>
				<!-- Tab panes -->
				<div class="tab-content">
					<div id="process" class="tab-pane pd-den-tab1 active">
                        <div class="container">
                            @if ($status)
                            <div class="row">
                                <div class="col-12">
                                    <h4>Payment receipt</h4>
                                    <p>Payment referrence</p>
                                    <p>{{ $payment->id }}</p>
                                    <p>Payment date-time</p>
                                    <p>{{ date('d/m/Y H:s', strtotime($payment->updated_at)) }}</p>
                                    <br>
                                    <table class="table" width="100%">
                                        @foreach ($bills as $bill)
                                        <tr>
                                            <td>{{ $bill['number'] }}</td>
                                            <td align="right">{{ @number_format(removeComma($bill['amount_total']), 2) }} THB</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                Total
                                            </td>
                                            <td align="right">
                                                {{ @number_format(removeComma($payment['total']), 2) }} THB
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" colspan="2">
                                                <!-- <br>
                                                <a class="btn btn-primary" id="payment-success" href="#">< กลับไปยัง Hexa Ceram Application</a> -->
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <a href="front/bill/home" class="btn btn-lg btn-primary">กลับไปหน้า Balance Due</a>
                                </div>
                            </div>
                            @else
                            <br><br>
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <div class="alert alert-danger">
                                        <h4>{{ $paymentRef['channel_response_desc'] }}</h4>
                                    </div>
                                    <br>
                                    <a href="front/bill/home" class="btn btn-lg btn-primary">กลับไปหน้า Balance Due</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
				</div>
				
			</div>
		</div>
	</div>
</div>
</div>
@endsection