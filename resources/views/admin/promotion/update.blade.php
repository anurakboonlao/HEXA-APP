@extends('layout.admin')
@section('content-header')
    <h1> แก้ไขโปรโมชั่น - {{ $promotion->id }} </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/promotion/' . $promotion->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">สินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::select('product_id', $products, $promotion->product_id, ['class' => 'form-control select2', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">ประเภท ( mobile app / web app )*</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::select('type', type_promotion(), $promotion->type, ['class' => 'form-control select2', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for=""> ข้อความบรรทัดที่ 1 </label>
                                    {{ Form::text('text', $promotion->text, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for=""> ข้อความบรรทัดที่ 2 </label>
                                    {{ Form::text('small_text', $promotion->small_text, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ลิ้งนอก</label>
                                    {{ Form::text('url', $promotion->url, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 5px;">
                                <div class="form-group">
                                    <label for="">เงื่อนไขการอัพรูป โปรโมชั่น * 
                                        <span class="label label-danger" style="font-size: small;"> Mobile App 800*600 pixcel</span>
                                        <span class="label label-danger" style="font-size: small;"> Web App 1200*408 pixcel </span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปแบนเนอร์ โปรโมชั่น
                                            </a>
                                        </span>
                                        {{ Form::text('image', $promotion->image, ['class' => 'form-control', 'id' => 'thumbnail-image', 'required']) }}
                                    </div>
                                    <img id="holde-image" src="{{ store_image($promotion->image) }}" style="margin-top:15px;max-height:100px;"><br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {{ Form::checkbox('public', '1', $promotion->public) }}
                                <label>เผยแพร่</label>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ (\URL::previous()) ? \URL::previous() : 'admin/category' }}" class="btn btn-default"><< กลับ</a>
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