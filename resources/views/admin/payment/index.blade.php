@extends('layout.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                {{ Form::open(['method' => 'get', 'autocomplete' => 'off']) }}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            {{ Form::select('member_id', ['' => 'ผู้จ่ายทั้งหมด'] + $members, '', ['class' => 'form-control select2']) }}
                        </div>
                        <div class="col-md-3">
                            {{ Form::select('type', ['' => 'รูปแบบการจ่ายทั้งหมด', 'online' => 'Online', 'bank_transfer' => 'โอน'], '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            {{ Form::date('start_date', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-md-3">
                            {{ Form::date('end_date', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::select('is_success', ['' => 'สถานะทั้งหมด', 1 => 'ยืนยันการรับชำระเงินแล้ว', 0 => 'รอยืนยันการรับชำระเงิน'], '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-success">ค้นหา</button>
                            <a target="_blank" class="btn btn-default" href="{{ request()->fullUrl() }}{{ (request()->input()) ? '&' : '?' }}type=export">Export Excel</a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>รหัส</th>
                            <th>หมายเลขใบวางบิล</th>
                            <th>ผู้จ่าย</th>
                            <th>วันที่</th>
                            <th>จำนวนเงิน</th>
                            <th>รายละเอียดการชำระเงิน</th>
                            <th>จำนวนที่ชำระ</th>
                            <th>สถานะ</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($payments as $key => $payment)
                            <tr>
                                <td>
                                    {{ ($payment->id) }}</td>
                                <td>
                                    @php
                                        $bills = json_decode($payment->bill_content, true)
                                    @endphp
                                    @foreach ($bills as $bill)
                                        <p>
                                            <a href="bill/{{ $bill['id'] }}" target="_blank">
                                                {{ $bill['number'] }} ({{ @number_format($bill['amount_total']) }})
                                            </a>
                                        </p>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $payment->member->name ?? '' }}
                                </td>
                                <td>{{ set_date_format($payment->created_at) }}</td>
                                <td align="right">{{ @number_format($payment->total, 2) }}</td>
                                <td align="right">
                                    {{ $payment['payment_type'] }} / ref : {{ $payment->ref1 }}
                                </td>
                                <td align="right">{{ @number_format($payment->total, 2) }}</td>
                                {{-- <td>{!! redemption_status($payment->is_success) !!}</td> --}}
                                <td>{!! redemption_status($payment) !!}</td>
                                <td>
                                    <?php
                                        if((($payment->payment_type == "transfer") || ($payment->payment_type == "qr credit") ) && (!$payment->is_success)){
                                            echo "<a href='admin/transfer/confirm?payment_transaction_id=$payment->id' 
                                                    class='btn btn-warning btn-xs'
                                                    onclick='return confirm(\"คุณต้องการยืนยันการชำระเงิน ใช่หรือไม่ (หากยืนยันแล้วจะไม่สามารถแก้ไขได้)\")'
                                                >
                                                    Confirm
                                                </a>";
                                        }
                                    ?>
                                    <a href="admin/payment/delete/{{ $payment->id }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-success">
                            <td align="right" colspan="6">รวม</td>
                            <td align="right">{{ @number_format($payments->sum('total'), 2) }}</td>
                            <td colspan="3"></td>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{ $payments->appends(request()->input())->links() }}
        </div>
    </div>

    <!-- The Modal/Lightbox -->


        <div id="myModal" class="modal">
            <span class="close cursor" onclick="closeModal()">&times;</span>
            <div class="modal-content">


                <!-- Next/previous controls -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>

                <!-- Caption text -->
                <div class="caption-container">
                    <p id="caption"></p>
                </div>

                
            </div>
        </div>

@endsection
@section('script')
<script type="text/javascript">
    $(function(e) {
        $('select.select2').select2();
    });

    function openModal() {
        document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
     showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        var captionText = document.getElementById("caption");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].alt;
    }

    function getTransferFiles(id){
        $.ajax({
            type: 'GET',
            url: {{url('admin/transfer/file')}},
            dataType: "JSON", // data type expected from server
            data: id,
            success: function (data) {
                console.log(data);
            },
            error: function(error) {
                console.log('Error: ' + error);
            }
        });
    }
    
</script>
@endsection