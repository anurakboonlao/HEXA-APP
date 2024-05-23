@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มข้อมูลบัญชีธนาคาร</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/bank']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รายละเอียดบัญชี *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('name', '', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 5px;">
                                <div class="form-group">
                                    <label for="">รูปโลโก้ธนาคาร *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> 500*500 pixcel
                                            </a>
                                        </span>
                                        {{ Form::text('image', '', ['class' => 'form-control', 'id' => 'thumbnail-image', 'required']) }}
                                    </div>
                                    <img id="holde-image" src="" style="margin-top:15px;max-height:100px;"><br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {{ Form::checkbox('status', '1') }}
                                <label>เผยแพร่</label>
                            </div>
                        </div>
                        <hr>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="admin/bank" class="btn btn-default"><< กลับ</a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
    </div>
@endsection
@section('script')
    <script src="vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="assets/jscolor-2.0.5/jscolor.js"></script>
    <script>
        $(function(e) {

            var domain = "{{ url('/') }}/laravel-filemanager";
            $('#image').filemanager('image', {prefix: domain});

        })
    </script>
@endsection