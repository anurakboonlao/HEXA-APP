@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มข้อมูลสินค้า </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/product']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">หมวดสินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::select('category_id', ['' => '-- หมวดสินค้า --'] + $categories, '', ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อสินค้า *</label>
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
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">ราคาสินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        {{ Form::number('price', '', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อธิบายสินค้า</label>
                                    {{ Form::textarea('description', '', ['class' => 'form-control']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รายละเอียดสินค้า</label>
                                    {{ Form::textarea('details', '', ['class' => 'form-control']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>ขนาดของภาพที่อัพโหลดควรมีขนาดเท่ากันทั้งหมดทุกรูป *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปปกสินค้า 600*800 pixcel
                                            </a>
                                        </span>
                                        {{ Form::text('image', '', ['class' => 'form-control', 'id' => 'thumbnail-image']) }}
                                    </div>
                                    <img id="holde-image" src="" style="margin-top:15px;max-height:100px;"><br>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {{ Form::checkbox('public', '1') }}
                                <label>เผยแพร่</label>
                            </div>
                        </div><hr>

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
</div>
@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script src="vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $(function(e) {
            var domain = "{{ url('/') }}/laravel-filemanager";

            $('#image').filemanager('image', {prefix: domain});
        })
    </script>
@endsection