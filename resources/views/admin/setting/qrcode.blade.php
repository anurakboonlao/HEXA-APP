@extends('layout.admin')
@section('content-header')
    <h1> รูปภาพ qrcode พร้อมเพล</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/setting/'. $setting->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 5px;">
                                <div class="form-group">
                                    <label for="">รูป โปรโมชั่น *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปหน้าปกขนาด สัดส่วน 1:1
                                            </a>
                                        </span>
                                        {{ Form::text('qrcode', $setting->qrcode, ['class' => 'form-control', 'id' => 'thumbnail-image', 'required']) }}
                                    </div>
                                    <img id="holde-image" src="{{ store_image($setting->qrcode) }}" style="margin-top:15px;max-height:200px;"><br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
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