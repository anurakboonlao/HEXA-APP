@extends('layout.admin')
@section('content-header')
    <h1> สินค้าขายดี</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {{ Form::open(['method' => 'get', 'id' => 'search']) }}
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-5 text-right">
                        {{ Form::date('start_date', '', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-5 text-right">
                        {{ Form::date('end_date', '', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-2">
                        <button class="btn btn-success" type="submit">
                            Filter <i class="fa fa-search"></i>
                        </button>
                        <button class="btn btn-default export-excel">
                            <i class="fa fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">อะไหล่</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>ลำดับที่</th>
                                    <th>จำนวนที่ขายได้</th>
                                    <th>อะไหล่</th>
                                    <th>เบอร์</th>
                                    <th>ใช้แทนรุ่น</th>
                                    <th>ราคาซื้อ</th>
                                    <th>ราคาขาย</th>
                                    <th>จำนวนอะไหล่คงเหลือ</th>
                                    <th>นำเข้าจาก</th>
                                    <th>สถานที่เก็บ</th>
                                </thead>
                                <tbody>
                                    @foreach ($lists as $key => $list)
                                    @php
                                        $product = \App\Product::find($list->product_id);
                                    @endphp
                                    @if ($product)
                                        <tr>
                                            <td>{{ ($key + 1) }}</td>
                                            <td>{{ $list->total_qty }}</td>
                                            <td>
                                                {{ product_name($product) }}
                                            </td>
                                            <td>
                                                {{ @$product->number }}
                                            </td>
                                            <td>
                                                {{ @$product->sub_version }}
                                            </td>
                                            <td>
                                                {{ @$product->buy_price_code }}
                                            </td>
                                            <td>
                                                {{ @$product->sale_price_code }}
                                            </td>
                                            <td align="right">
                                                {{ @number_format($product->qty) }}
                                            </td>
                                            <td>
                                                {{ @$product->in_from }}
                                            </td>
                                            <td>
                                                {{ @$product->location }}
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
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
            $('.export-excel').on('click', function(e) {
                e.preventDefault();
                
                var start_date = $("input[name='start_date']").val()
                var end_date = $("input[name='end_date']").val()

                window.location = "admin/report/best_product_excel?start_date=" + start_date + "&end_date=" + end_date;
            })
        })
    </script>
@endsection