@extends('layout.admin')
@section('content-header')
    <h1> Feedback Eorder</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Feedback Eorder</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{ Form::open(['method' => 'get']) }}
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="date">หมายเลข Eorder</label>    
                                {{ Form::text('id', '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                    <th>#</th>
                                    <th>วันที่</th>
                                    <th>โค้ด</th>
                                    <th>
                                        รายละเอียด
                                       <br>
                                       คนไข้/แพทย์/ลูกค้า
                                    </th>
                                    <th>คะแนน</th>
                                    <th>ความคิดเห็น</th>
                                    <th>ลูกค้า</th>
                                </thead>
                                <tbody>
                                    @foreach ($comments as $key => $comment)
                                    @php
                                        $detail = json_decode($comment->content);
                                    @endphp
                                    <tr>
                                        <td>{{ (($key + 1) * (request()->input('page') + 1)) }}</td>
                                        <td>{{ set_date_format($comment->updated_at) }}</td>
                                        <td>{{ $detail->e_code }}</td>
                                        <td>
                                            {{ $detail->pat_name }} / {{ $detail->doc_name }} / {{ $detail->cus_name }}
                                        </td>
                                        <td>
                                            {{ $comment->rate }}
                                        </td>
                                        <td>{{ $comment->message }}</td>
                                        <td>{{ $comment->member->name ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $comments->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection