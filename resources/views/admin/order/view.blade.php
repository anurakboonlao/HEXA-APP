@extends('layout.admin')
@section('content-header')
<h1> รายละเอียดการสั่งซื้อ </h1>
@endsection
@section('content')
<section class="invoice" id="invoice">
    {{ Form::open(['method' => 'PUT', 'url' => request()->fullUrl()]) }}
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-globe"></i> Hexa Ceram
            <small class="pull-right">Date: {{ set_date_format($order->date) }}</small>
        </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
        ลูกค้า
        <address>
            <strong>{{ $order->member->name }}</strong><br>
            {{ $order->addtress }}<br>
            Phone: {{ $order->phone }}<br>
            Email: <a href="mailTo:{{ $order->email }}">{{ $order->email }}</a>
        </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
        <b>Order #{{ order_number($order->id) }}</b><br>
        <br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->products ?? [] as $key => $list)    
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->product->name }} Size: {{ $list->size }} Color: {{ $list->color }}</td>
                            <td>{{ $list->qty }}</td>
                            <td>{{ number_format($list->price) }}</td>
                            <td align="right">{{ number_format($list->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
            
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="90%">Total:</td>
                            <td align="right">{{ number_format($order->total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-xs-12">
            <a href="{{ request()->fullUrl() }}" onclick="printElem('invoice')" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            @if ($order->status)
                <button type="submit" class="btn btn-danger pull-right"><i class="fa fa-check"></i> Cancel confirmed Order</button>
            @else
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Confirm Order</button>
            @endif
        </div>
    </div>
    {{ Form::close() }}
</section>
@endsection
@section('script')
    <script>
        function printElem(elements) {
            var headstr = "<html><head><title>ใบสั่งซื้อ</title></head><body>";
            var footstr = "</body>";
            var newstr = document.getElementById(elements).innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr + newstr + footstr;
            window.print();
            document.body.innerHTML = oldstr;
            return false;
        }
    </script>
@endsection