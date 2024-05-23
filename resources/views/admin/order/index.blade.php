@extends('layout.admin')
@section('content-header')
    <h1> การสั่งซื้อ </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">การสั่งซื้อ </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::select('status', ['' => 'สถานะทั้งหมด'] + order_status(), '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::text('key', '', ['class' => 'form-control', 'placeholder' => 'รหัส, อีเมล, ที่อยู่']) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-5">
                            <div class="form-group">
                                <label for="date">ตั้งแต่วันที่</label>    
                                {{ Form::date('start_date', '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-5">
                            <div class="form-group">
                                <label for="date">ถึงวันที่</label>    
                                {{ Form::date('end_date', '', ['class' => 'form-control']) }}
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                            <a target="_blank" class="btn btn-default" href="{{ request()->fullUrl() }}{{ (request()->input()) ? '&' : '?' }}type=export">Export Excel</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <hr>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-primary">
                                    <th>วันที่</th>
                                    <th>รหัส</th>
                                    <th>สินค้า</th>
                                    <th>ผู้สั่งซื้อ</th>
                                    <th>ยอดเงินที่ต้องชำระ</th>
                                    <th>สถานะ</th>
                                    <th width="60"></th>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ set_date_format($order->date) }}</td>
                                        <td>{{ order_number($order->id) }}</td>
                                        <td>
                                            @foreach ($order->products ?? [] as $item)
                                                {{ $item->product->name ?? '' }} จำนวน {{ $item->qty ?? '' }} สี {{ $item->color ?? '' }} ไซต์ {{ $item->size ?? '' }} <br>
                                            @endforeach
                                        </td>
                                        <td>{{ $order->member->name ?? null }}</td>
                                        <td>{{ number_format($order->total) }}</td>
                                        <td>{!! order_status(($order->status) ? 'success' : 'new') !!}</td>
                                        <td style="width: 100px;">
                                            <a href="admin/order/{{ $order->id }}" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="admin/order/delete/{{ $order->id }}" class="btn btn-danger btn-xs">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $orders->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(function(e) {
        $('a.view-order').on('click', function(e) {
            e.preventDefault()
            
            $.get(e.currentTarget.href, function(html) {
                
            })

        })
    })
</script>
@endsection