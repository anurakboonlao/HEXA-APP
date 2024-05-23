@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มข้อมูลทีมงาน </h1>
@endsection
@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice { color: #333;}
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        font-size: 16px;
        float: right;
        margin-right: 0;
        margin-left: 5px;
        border-left: 1px solid #ccc;
        padding-left: 5px;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            @include('admin.elements.errors_validate')
            {{ Form::open(['files' => true, 'url' => 'admin/member']) }}
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ระดับการใช้งาน</label>
                                    {{ Form::select('role', role_member(), '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อเต็ม *</label>
                                    {{ Form::text('name', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อีเมล *</label>
                                    {{ Form::email('email', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">เบอร์โทรศัพท์</label>
                                    {{ Form::text('phone', '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อผู้ใช้ *</label>
                                    {{ Form::text('username', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รหัสผ่าน *</label>
                                    {{ Form::password('password', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">โซนที่ดูแลสำหรับ Sale ( สามารถเลือกโซน ได้มากกว่า 1 รายการ )</label>
                                    {{ Form::select('zone_id[]', $zones, '', ['class' => 'form-control zone-multiple', 'multiple', 'style' => 'width: 100%!important']) }}                                
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">Line id</label>
                                    {{ Form::text('line_id', '',  ['class' => 'form-control']) }}
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.zone-multiple').select2({
                placeholder: ' -- เลือกโซนที่ดูแลสำหรับ Sale --'
            });
        });
    </script>
@endsection