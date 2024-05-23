<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>รายละเอียดการสั่งซื้อหมายเลข #{{ $order->id }}</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<style>
.head {background-color: #ececec;}
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
        <section class="invoice">
            <div class="row invoice-info" style="padding: 1.5rem;">
                <h4>ยืนยันคำสั่งซื้อหมายเลข #{{ $order->id }}</h4><br>
                สวัสดี คุณ XXXXXXXXXXX <br><br>
                ขอขอบคุณสำหรับการช้อปปิ้งที่ Mister&Miss แหล่งบริการช้อปปิ้งออนไลน์ <br>
                คำสั่งซื้อของคุณอยู่ในระหว่างการตรวจสอบ ทางเราจะส่งอีเมลล์แจ้งให้ทราบถึงเวลาการจัดส่งสินค้า ในลำดับต่อไป <br><br>
                เพื่อการอ้างอิงในอนาคต หมายเลขสั่งซื้อของคุณคือ XXXXXXXXXXXX โดยทางเราจะทำการอัพเดทสถานะของคำสั่งซื้อนี้ให้คุณทราบเป้นระยะ ทั้งทางอีเมล และข้อความ SMS
                คุณสามารถตรวจสอบสถานะผ่านระบบออนไลน์ได้ตลอดเวลา โดยการกดเลือกด้านล่าง
            </div>
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                    <i class="fa fa-globe"></i> MR&MS
                    <small class="pull-right">วันที่ : {{ date('d-m-Y H:i', strtotime($order->created_at)) }} น.</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
                <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    ที่อยู่สำหรับการจัดส่ง
                    <address>
                    <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_district }}, {{ $order->shipping_province }} {{ $order->shipping_postcode }}<br>
                    โทร : {{ $order->shipping_tel }}<br>
                    อีเมล : {{ $order->shipping_email }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>เลขที่การสั่งซื้อ #{{ $order->id }}</b>
                </div>
                <!-- /.col -->
            </div>
                <!-- /.row -->

                <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>สินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>รวมราคา</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $orderTotal = 0;
                            @endphp
                            @foreach ($order->lists as $key => $item)    
                                <tr>
                                    <td> {{ $key + 1 }} </td>
                                    <td class="text-left">
                                        <p><span>{{ $item->product->category->name ?? '' }}</span>/ {{ $item->product->name ?? '' }} Size : <b>{{ $item->productSize->name ?? '' }}</b></p>
                                    </td>
                                    <td class="text-right">{{ @number_format($item->qty) }}</td>
                                    <td class="text-right">{{ @number_format($item->price) }}</td>
                                    <td class="text-right">{{ number_format($item->amount_total) }}</td>
                                </tr>
                            @php
                                $orderTotal += $item->amount_total;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
                <!-- /.row -->

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ยอดสั่งซื้อทั้งหมด</th>
                                    <td class="text-right">{{ @number_format($orderTotal) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12" id="payment-details">
                    
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-xs-12">
                    <a href="" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> 
                        อัพเดทใบสั่งซื้อ
                    </button>
                </div>
            </div>
        </section>
    </div>
<!-- ./wrapper -->
</body>
</html>