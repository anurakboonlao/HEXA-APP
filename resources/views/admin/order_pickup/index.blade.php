@extends('layout.admin')
@section('content-header')
    <h1> Order Pickups</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Order Pickups</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::select('checked', ['' => 'สถานะทั้งหมด', 0 => 'ยังไม่ได้รับงาน', 1 => 'รับงานแล้ว'], '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::select('sale_id', ['' => 'ผู้รับงานทั้งหมด'] + $members, '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::select('branch_id', ['' => 'สาขาทั้งหมด'] + $branches, '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::text('key', '', ['class' => 'form-control', 'placeholder' => 'ค้นหาตาม ชื่อ, อีเมล, ที่อยู่ ลูกค้า']) }}
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
                        <div class="col-md-2">
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
                                    <th>รหัสงาน</th>
                                    <th>คลีนิค</th>
                                    <th>วันเวลา</th>
                                    <th>จำนวนเคส</th>
                                    <th>สถานที่</th>
                                    <th>หมายเหตุ</th>
                                    <th>วันเวลา<br>ที่ทำรายการ</th>
                                    <th>สถานะ</th>
                                    <th>ผู้รับงาน</th>
                                    <th>เวลารับงาน</th>
                                    <th>สถานที่รับงาน</th>
                                    <th width="30"></th>
                                </thead>
                                <tbody>
                                    @foreach ($pickups as $pickup)
                                    <tr>
                                        <td>{{ order_number($pickup->id) }}</td>
                                        <td>{{ $pickup->member->name ?? '' }}</td>
                                        <td>
                                            วันที่ {{ set_date_format($pickup->created_at) }} <br>
                                            เวลา {{ ($pickup->time == '00:00-18:00') ? $pickup->urgent_time : $pickup->time }}
                                        </td>
                                        <td>{{ $pickup->total_case }}</td>
                                        <td>{{ $pickup->address }}</td>
                                        <td>{{ $pickup->clinic_note }}</td>
                                        <td width="120">
                                            วันที่ {{ set_date_format($pickup->created_at) }} <br>
                                            เวลา {{ set_time_format($pickup->created_at) }}
                                        </td>
                                        <td>
                                            <a href="admin/order_pickup/update/{{ $pickup->id }}">
                                                {!! pickup_status($pickup->checked) !!}
                                            </a>
                                        </td>
                                        <td width="120">
                                            {{ $pickup->sale->name ?? '' }}
                                        </td>
                                        <td width="80">
                                            {{ ($pickup->checked) ? $pickup->updated_at : '' }}
                                        </td>
                                        <td>
                                            <a target="_blank" href="http://www.google.com/maps/place/{{ $pickup->lat }},{{ $pickup->long }}">
                                                เปิด map
                                            </a>
                                        </td>
                                        <td style="width: 100px;">
                                            <a href="admin/order_pickup/delete/{{ $pickup->id }}" class="btn btn-danger btn-xs">
                                                Remove <i class="fa fa-remove" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $pickups->appends(request()->input())->links() }}
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