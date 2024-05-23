@extends('layout.admin')
@section('content-header')
    <h1> ลูกค้า {{ (request()->has('verify')) ? (request()->input('verify') == 'true') ? 'ที่ Invite Code แล้ว' : 'ที่ยังไม่ได้ Invite Code' : '' }}</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">ลูกค้า</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                {{ Form::select('customer_type', ['' => 'ระดับการใช้งานทั้งหมด'] + type_customer(), '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {{ Form::text('key', '', ['class' => 'form-control', 'placeholder' => 'รหัส, ชื่อ, อีเมล, เบอร์โทรศัพท์, ชื่อผู้ใช้']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                            <a target="_blank" class="btn btn-default" href="{{ request()->fullUrl() }}{{ (request()->input()) ? '&' : '?' }}type=export">Export Excel</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-primary">
                                    <th>รหัสลูกค้า</th>
                                    <th>ระดับการใช้งาน</th>
                                    <th>ชื่อเต็ม</th>
                                    <th>ที่อยู่</th>
                                    <th>อีเมล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ชื่อผู้ใช้</th>
                                    <th>วันที่ใช้งานระบบ</th>
                                    <th>โซน</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $key => $customer)
                                    @php
                                        $zoneMember = \App\ZoneMember::where('member_id', $customer->id)->first();
                                        $zoneId = (empty($zoneMember)) ? 0 : $zoneMember->zone_id;
                                        $zone = \App\Zone::find($zoneId);
                                    @endphp
                                    <tr>
                                        <td> 
                                            @if(!$customer->customer_verify_key)
                                            <input type="text" class="form-control" disabled>
                                            @else
                                                {{ $customer->customer_verify_key }} 
                                            @endif
                                        </td>
                                        <td> {{ (!$customer->type) ? "ทั่วไป" : type_customer($customer->type) }} </td>
                                        <td> {{ $customer->name }} </td>
                                        <td> {{ $customer->address }} </td>
                                        <td> {{ $customer->email }} </td>
                                        <td> {{ $customer->phone }} </td>
                                        <td> {{ $customer->username }} </td>
                                        <td> {{ set_date_format($customer->created_at) }} </td>
                                        <td>
                                            @if (!empty($zone))
                                                ({{ $zone->id }}) {{ $zone->name }}
                                            @endif
                                        </td>
                                        <td width="80">
                                            @if($customer->customer_verify_key)
                                            <a href="admin/edit_customer/{{ $customer->id }}" class="btn btn-warning btn-sm" title="แก้ไขข้อมูล Username และ Password ลูกค้า">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            @endif
                                            <a href="admin/member/delete/{{ $customer->id }}?role={{ $customer->role }}" class="btn btn-danger btn-sm" title="ลบข้อมูลลูกค้า">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customers->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection