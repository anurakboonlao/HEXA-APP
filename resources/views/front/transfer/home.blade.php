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
				<div class="font-denti1 dis-rewa pd-balance1"><h2>ช่องทางการชำระเงิน</h2></div>

				<!-- Nav tabs -->
				{{-- <ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
					  <a class="nav-link active" data-toggle="tab" href="#process">รายการชำระ</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" data-toggle="tab" href="#complete">ประวัติการชำระเงิน</a>
					</li>
				</ul> --}}
				<!-- Tab panes -->
				<div class="tab-content">
					<div id="process" class="tab-pane pd-den-tab1 active">
						<div class="row d-flex justify-content-center">

                            <img src="{{ asset('/images/qr_credit.png') }}" alt="Image" style="height: 400px; margin: 25px;" />
                            <img src="{{ asset('/images/qr_transfer.png') }}" alt="Image" style="height: 400px; margin: 25px;"/>
						</div>
                        <hr>
						<div class="mdl-card">
							<div class="mdl-card__supporting-text">
                                <div class="font-denti1 dis-rewa pd-balance1"><h2>แนบหลักฐานการโอนเงิน</h2></div>
							</div>
                            <div class="row col-4">
                                <form action="{{ route('transfer.upload') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ Form::hidden('payment_transaction_id', $payment_transaction_id) }}
                                    <div>
                                        <div class="mt-3">
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-success " style="color:#FFFFFF;">อัพโหลดสลิป</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
						</div>
						<div class="form-group form-inline">
                            @foreach ($payment_transaction_files as $file)
                                <div class="img-wrap" id="file_{{ $file->id }}">
                                    <img src="{{ asset('/uploads/payments/' . $file->file_path) }}" alt="" height="150px">
                                    <a href="{{ url('front/transfer/delete?payment_transaction_id=' . $payment_transaction_id . '&payment_transaction_file_id=' . $file->id) }}" onclick="return deleteFile('file_{{ $file->id }}')"><img src="{{ asset('/images/delete.png') }}" alt="" height="20px" class="delete-image"></a>
                                </div>
                            @endforeach
                            
                        </div><br><br>
                        <div class="d-flex justify-content-center">
                            
                            <form action="{{ url('front/transfer/confirm') }}" method="GET" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                {{ Form::hidden('payment_transaction_id', $payment_transaction_id) }}
                                <div class="justify-content-center mdl-card__supporting-text" style="text-align: center">
                                    <div class="font-denti1 dis-rewa pd-balance1"><h2>ชำระเงินแบบ</h2></div>
                                </div><br>
                                <div class="row">
                                    <label class="radio-container">QR บัตรเครดิต
                                        <input type="radio" name="payment_type" value="qr credit"
                                            @IF($payment_type == "qr credit")
                                                checked
                                            @ENDIF

                                        >
                                        <span class="checkmark"></span>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label class="radio-container">เงินโอน
                                        <input type="radio" name="payment_type" value="transfer"
                                            @IF($payment_type == "transfer")
                                                checked
                                            @ENDIF
                                        >
                                        <span class="checkmark"></span>
                                    </label>
                                </div><br>
                                <div class="justify-content-center" style="text-align: center">
                                    <button type="submit" class="btn btn-success " style="">ยืนยันการโอนเงิน</button>
                                    <a href="{{ url('front/dashboard') }}"><button type="button" class="btn btn-danger " style="color:#FFFFFF; margin: 15px;">กลับสู่หน้าหลัก</button></a>
                                </div>
                                {{-- <a href="{{ url('front/transfer/confirm?payment_transaction_id=' . $payment_transaction_id) }}"><button type="button" class="btn btn-success ">ยินยันการโอนเงิน</button></a> --}}
                            </form>
                            
                        </div>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
</div>

<!-- Modal bill-->
{{-- <div class="modal" id="modal-bill" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
</div> --}}

<!-- Modal Preview-->
{{-- <div class="modal" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
</div> --}}

</div>
@endsection
@section('script')
    {{-- <script src="{{ asset("template/front/js/my_order/script.js") }}"></script> --}}
    <script>
        function deleteFile(element_id){

            if(confirm('คุณต้องการที่จะ ลบ ใช่หรือไม่')){
                const element = document.getElementById(element_id);
                element.remove();
                return true;
            }
            return false;
        }
    </script>
@endsection

