@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มผู้ใช้งาน </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            @include('admin.elements.errors_validate')
            {{ Form::open(['files' => true]) }}
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ระดับผู้ใช้</label>
                                    {{ Form::select('role', role_user(), '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อพนักงาน *</label>
                                    {{ Form::text('name', '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อีเมล *</label>
                                    {{ Form::text('email', '', ['class' => 'form-control']) }}
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="banner_image" data-input="thumbnail" data-preview="holde" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพประจำตัว
                                        </a>
                                    </span>
                                    {{ Form::text('image', '', ['class' => 'form-control', 'id' => 'thumbnail']) }}
                                </div>
                                <img id="holde" src="" style="margin-top:15px;max-height:100px;">
                                <br>
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
                                    <a href="admin/user/index" class="btn btn-default"><< กลับ</a>
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
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script src="vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $(function(e) {

            var domain = "{{ url('/') }}/laravel-filemanager";

            $('#banner_image').filemanager('image', {prefix: domain});
        })
    </script>
@endsection