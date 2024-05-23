@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มข้อมูล VoucherBanner </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/voucher_banner']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อแบนนเนอร์</label>
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-link"></i>
                                        </div>
                                        {{ Form::text('url', '', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ลำดับการแสดง</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-sort-numeric-asc"></i>
                                        </div>
                                        {{ Form::text('sort', '', ['class' => 'form-control']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 5px;">
                                <div class="form-group">
                                    <label for="">รูป Voucher *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปแบนเนอร์ Voucher 600*800 pixcel
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
                                {{ Form::checkbox('public', '1') }}
                                <label>เผยแพร่</label>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ (\URL::previous()) ? \URL::previous() : 'admin/voucher_banner' }}" class="btn btn-default"><< กลับ</a>
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