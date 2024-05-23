@extends('layout.admin')
@section('content-header')
    <h1> Checkings</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Checkings</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::select('member_id', ['' => 'พนักงานทั้งหมด'] + $members, '', ['class' => 'form-control']) }}
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
                                    <th>วันเวลาบันทึก</th>
                                    <th>พนักงาน</th>
                                    <th>เวลาที่กรอก</th>
                                    <th>สถานที่</th>
                                    <th>GPS</th>
                                    <th width="30"></th>
                                </thead>
                                <tbody>
                                    @foreach ($checkings as $checking)
                                    <tr>
                                        <td>{{ $checking->created_at }}</td>
                                        <td>{{ $checking->member->name ?? '' }}</td>
                                        <td>
                                            {{ $checking->time }}
                                        </td>
                                        <td>{{ $checking->location }}</td>
                                        <td>
                                            <a target="_blank" href="http://www.google.com/maps/place/{{ $checking->lat }},{{ $checking->long }}">    
                                                {{ $checking->lat }},{{ $checking->long }}
                                            </a>
                                        </td>
                                        <td style="width: 100px;">
                                            <a href="admin/order/delete/{{ $checking->id }}" class="btn btn-danger btn-xs">
                                                Remove <i class="fa fa-remove" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $checkings->appends(request()->input())->links() }}
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