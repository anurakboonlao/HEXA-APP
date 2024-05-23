@extends('layout.admin')
@section('content-header')
    <h1> แก้ไขข้อมูลทีมงาน </h1>
@endsection
@section('content')
<style>
    .select2-container .select2-selection--single .select2-selection__rendered { display: unset !important;}
    .select2-container--bootstrap { width: 100% !important; }
</style>
<div class="row">
    <div class="col-md-8 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">ข้อมูลทีมงาน</h4>
            </div>
            <div class="box-body">
            @include('admin.elements.errors_validate')
            {{ Form::open(['files' => true, 'url' => 'admin/member/'. $member->id, 'method' => 'PUT']) }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ระดับการใช้งาน</label>
                                    {{ Form::select('role', role_member(), $member->role, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อเต็ม *</label>
                                    {{ Form::text('name', $member->name, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อีเมล *</label>
                                    {{ Form::email('email', $member->email, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">เบอร์โทรศัพท์</label>
                                    {{ Form::text('phone', $member->phone, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อผู้ใช้ *</label>
                                    {{ Form::text('username', $member->username, ['class' => 'form-control', 'disabled']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รหัสผ่าน *</label>
                                    {{ Form::password('password', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">Line id</label>
                                    {{ Form::text('line_id', $member->line_id, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-floppy-o"></i>
                                        บันทึกข้อมูล
                                    </button>
                                    <a href="admin/member" class="btn btn-default"><< กลับ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">ข้อมูลโซนที่ดูแล **<span style="font-size: 16px;">( สามารถเลือกได้มากกว่า 1 รายการ )</span></h4>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('admin.elements.errors_validate')
                        {{ Form::open(['files' => true, 'url' => 'admin/zone_member']) }}
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {{ Form::select('zone_id', ['' => '-- เลือกโซนที่ดูแลสำหรับ Sale --'] + $zones, '', ['class' => 'form-control select2', 'required']) }}
                                    {{ Form::hidden('member_id', $member->id) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-floppy-o"></i> เพิ่มโซนที่ดูแล</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered">
                                    <thead class="bg-gray">
                                        <th class="text-center">#</th>
                                        <th class="text-center">โซนที่ดูแล</th>
                                        <th class="text-center">ลบ</th>
                                    </thead>
                                    <tbody>
                                        @foreach($zone_members as $key => $zone)
                                        <tr class="text-center">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $zone->zone->name ?? '' }}</td>
                                            <td>
                                                <a href="admin/zone_member/delete/{{ $zone->id }}" class="btn btn-danger btn-xs">
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
<div>
@endsection