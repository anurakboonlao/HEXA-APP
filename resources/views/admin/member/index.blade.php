@extends('layout.admin')
@section('content-header')
    <h1> ทีมงาน</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">ทีมงาน</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>ระดับการใช้งาน</th>
                                    <th>โซนที่ดูแล</th>
                                    <th>ชื่อเต็ม</th>
                                    <th>อีเมล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>Line Id</th>
                                    <th>Validation Code</th>
                                    <th>เชื่อมต่อ Line</th>
                                    <th>ชื่อผู้ใช้</th>
                                    <th width="60"></th>
                                </thead>
                                <tbody>
                                    @foreach ($members as $key => $member)
                                    <tr>
                                        <td> {{ role_member($member->role) }} </td>
                                        <td>
                                            @if($member->zone_members)
                                                @foreach($member->zone_members as $key => $zone_member)
                                                    <span class="label label-primary" style="font-size: 12px; font-weight: 500; line-height: 2;">{{ $zone_member->zone->name ?? '' }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td> {{ $member->name }} </td>
                                        <td> {{ $member->email }} </td>
                                        <td> {{ $member->phone }} </td>
                                        <td> {{ $member->line_id }} </td>
                                        <td> {{ $member->line_secret_code }} </td>
                                        <td> 
                                            @if(!empty($member->line_user_id))
                                                <span class="label label-success" style="font-size: 12px; font-weight: 500; line-height: 2;">{{ 'Yes' }}</span>
                                            @else
                                                <span class="label label-danger" style="font-size: 12px; font-weight: 500; line-height: 2;">{{ 'No' }}</span>
                                            @endif
                                        </td>

                                        <td> {{ $member->username }} </td>
                                        <td>
                                            <a href="admin/member/{{ $member->id }}/edit" class="btn btn-default btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/member/delete/{{ $member->id }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
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