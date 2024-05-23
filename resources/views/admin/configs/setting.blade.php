@extends('layout.admin')
@section('content-header')
    <h1> ตั้งค่าหน้าเว็บ </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/configs/'. $config->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* ภาพควรอัพเป็น 1454 x 924px</span>
                                <div class="input-group">
                                    {{ Form::text('home_banner_1', $config->home_banner_1, ['class' => 'form-control', 'id' => 'thumbnail1']) }}
                                    <span class="input-group-btn">
                                        <a id="home_banner_1" data-input="thumbnail1" data-preview="holde1" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพ แบนเนอร์1
                                        </a>
                                    </span>
                                </div>
                                <img id="holde1" src="{{ store_image($config->home_banner_1) }}" style="margin-top:15px;max-height:100px;">
                                <br><br>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* ภาพควรอัพเป็น 1026 x 1000px</span>
                                <div class="input-group">
                                    {{ Form::text('home_banner_2', $config->home_banner_2, ['class' => 'form-control', 'id' => 'thumbnail2']) }}
                                    <span class="input-group-btn">
                                        <a id="home_banner_2" data-input="thumbnail2" data-preview="holde2" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพ แบนเนอร์2
                                        </a>
                                    </span>
                                </div>
                                <img id="holde2" src="{{ store_image($config->home_banner_2) }}" style="margin-top:15px;max-height:100px;">
                                <br><br>
                            </div>                      
                        </div>
                        <div class="row">   
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* ภาพควรอัพเป็น 100 x 100px</span>
                                <div class="input-group">
                                    {{ Form::text('gif_1', $config->gif_1, ['class' => 'form-control', 'id' => 'thumbnail3']) }}
                                    <span class="input-group-btn">
                                        <a id="gif_1" data-input="thumbnail3" data-preview="holde3" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพ Gif1
                                        </a>
                                    </span>
                                </div>
                                <img id="holde3" src="{{ store_image($config->gif_1) }}" style="margin-top:15px;max-height:100px;">
                                <br><br>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* ภาพควรอัพเป็น 1454 x 1000px</span>
                                <div class="input-group">
                                    {{ Form::text('gif_2', $config->gif_2, ['class' => 'form-control', 'id' => 'thumbnail4']) }}
                                    <span class="input-group-btn">
                                        <a id="gif_2" data-input="thumbnail4" data-preview="holde4" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพ Gif2
                                        </a>
                                    </span>
                                </div>
                                <img id="holde4" src="{{ store_image($config->gif_2) }}" style="margin-top:15px;max-height:100px;">
                                <br><br>
                            </div>                    
                        </div>
                        <div class="row">                       
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* ภาพแบนเนอร์ที่อยู่ในเมนู</span>
                                <div class="input-group">
                                    {{ Form::text('nav_banner', $config->nav_banner, ['class' => 'form-control', 'id' => 'thumbnail']) }}
                                    <span class="input-group-btn">
                                        <a id="nav_banner" data-input="thumbnail" data-preview="holde" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> รูปภาพ แบนเนอร์เมนู
                                        </a>
                                    </span>
                                </div>
                                <img id="holde" src="{{ store_image($config->nav_banner) }}" style="margin-top:15px;max-height:100px;">
                                <br><br>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span class="label label-primary">* วิดีโอ</span>
                                <div class="form-group">
                                    {{ Form::textarea('home_video', $config->home_video, ['class' => 'form-control', 'rows' => 6]) }}
                                </div>
                                <div>
                                    {!! $config->home_video !!}
                                </div>
                            </div>
                        </div><hr>
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
    </div>
</div>
    {{ Form::close() }}

@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>   
    <script src="vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="assets/jscolor-2.0.5/jscolor.js"></script>
    <script>
        $(function(e) {

           //CKEDITOR.replace('detail', options);

            var domain = "{{ url('/') }}/laravel-filemanager";

            $('#nav_banner').filemanager('image', {prefix: domain});
            $('#home_banner_1').filemanager('image', {prefix: domain});    
            $('#home_banner_2').filemanager('image', {prefix: domain});
            $('#gif_1').filemanager('image', {prefix: domain});
            $('#gif_2').filemanager('image', {prefix: domain});

        })
    </script>
@endsection