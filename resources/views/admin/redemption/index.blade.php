@extends('layout.admin')
@section('content-header')
    <h1> Redemptions </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Redemptions </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::select('approved', ['' => 'สถานะทั้งหมด', 0 => 'รอตรวจสอบ', 1 => 'อนุมัติแล้ว'], '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::select('voucher_id', ['' => 'บัตรกำนัลทั้งหมด'] + $vouchers, '', ['class' => 'form-control select2']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::select('member_id', ['' => 'ลูกค้าทั้งหมด'] + $members, '', ['class' => 'form-control select2']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-5">
                            <div class="form-group">
                                <label for="date">ตั้งแต่วันที่</label>    
                                {{ Form::date('start_date', '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-5">
                            <div class="form-group">
                                <label for="date">ถึงวันที่</label>    
                                {{ Form::date('end_date', '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                            <a target="_blank" href="admin/redemption_export?{{ http_build_query(request()->input()) }}" class="btn btn-default">Export Excel</a>
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
                                    <th>รหัส</th>
                                    <th>วันที่แลก</th>
                                    <th>ผู้แลกรับ</th>
                                    <th>Voucher</th>
                                    <th>ใช้แต้ม</th>
                                    <th>จำนวนเงิน</th>
                                    <th width="130">อนุมัติการแลกรับ</th>
                                    <th width="30"></th>
                                </thead>
                                <tbody>
                                    @foreach ($redemptions as $redemption)
                                    <tr>
                                        <td>
                                            <a href="admin/redemption/{{ $redemption->id }}?return={{ request()->fullUrl() }}">
                                                {{ $redemption->code }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ set_date_format($redemption->created_at) }}
                                        </td>
                                        <td>{{ (!$redemption->name) ? $redemption->member->name ?? '' : $redemption->name }}</td>
                                        <td>
                                            <a href="admin/voucher/{{ $redemption->voucher_id }}/edit" target="_blank">
                                                {{ $redemption->voucher->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $redemption->point }}
                                        </td>
                                        <td>
                                            {{ $redemption->amount }}
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="redemptions" data-field="approved" data-id="{{ $redemption->id }}" data-type="text" type="checkbox" value="1" {{ ($redemption->approved) ? "checked" : "" }}>
                                        </td>
                                        <td style="width: 100px;">
                                            @if (!$redemption->approved)
                                                <a href="admin/redemption/delete/{{ $redemption->id }}" class="btn btn-danger btn-xs">
                                                    Remove <i class="fa fa-remove" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $redemptions->appends(request()->input())->links() }}
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