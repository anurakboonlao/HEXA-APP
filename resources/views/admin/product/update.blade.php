@extends('layout.admin')
@section('content-header')
    <h1> สินค้า - {{ $product->name }} </h1>
@endsection
@section('content')
@include('admin.elements.errors_validate')
<div class="row">
    <div class="col-xs-6">
    {{ Form::open(['files' => true, 'url' => 'admin/product/'. $product->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">หมวดสินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::select('category_id', ['' => '-- หมวดสินค้า --'] + $categories, $product->category_id, ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อสินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('name', $product->name, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ราคาสินค้า *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        {{ Form::number('price', $product->price, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ขนาดของสินค้า ตัวอย่างการใช่ข้อมูลเช่น --> เบอร์ 1,เบอร์ 2,เบอร์ 3</label>
                                    {{ Form::textarea('sizes', $product->sizes, ['class' => 'form-control', 'rows' => 2]) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">สีของสินค้า ตัวอย่างการใช่ข้อมูลเช่น --> สีส้ม,สีแดง,สีน้ำเงิน</label>
                                    {{ Form::textarea('colors', $product->colors, ['class' => 'form-control', 'rows' => 2]) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อธิบายสินค้า</label>
                                    {{ Form::textarea('description', $product->description, ['class' => 'form-control']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รายละเอียดสินค้า</label>
                                    {{ Form::textarea('details', $product->details, ['class' => 'form-control']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>ขนาดของภาพที่อัพโหลดควรมีขนาดเท่ากันทั้งหมดทุกรูป *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปปกสินค้า 600*800 pixcel
                                            </a>
                                        </span>
                                        {{ Form::text('image', $product->image, ['class' => 'form-control', 'id' => 'thumbnail-image']) }}
                                    </div>
                                    <img id="holde-image" src="{{ store_image($product->image) }}" style="margin-top:15px;max-height:100px;"><br>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {{ Form::checkbox('public', '1', $product->public) }}
                                <label>เผยแพร่</label>
                            </div>
                        </div><hr>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ (\URL::previous()) ? \URL::previous() : 'admin/item' }}" class="btn btn-default"><< กลับ</a>
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
    <div class="col-xs-6">
    {{ Form::open(['files' => true, 'url' => 'admin/product_discount']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4>เงื่อนไขราคาพิเศษ</h4>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">จำนวนขั้นต่ำที่สั่งซื้อ *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::hidden('product_id', $product->id) }}
                                        {{ Form::number('qty', '', ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ราคาขายพิเศษ *</label>
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
                            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="box-body">
                        <table class="table data-table">
                            <thead>
                                <th>#</th>
                                <th>จำนวนขั้นต่ำที่สั่งซื้อ</th>
                                <th>ราคาขายพิเศษ</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($product->discounts as $key => $item)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="product_discounts" data-field="qty" data-id="{{ $item->id }}" data-reload="false" data-item="text">
                                                {{ $item->qty }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="product_discounts" data-field="price" data-id="{{ $item->id }}" data-reload="false" data-item="text">
                                                {{ $item->price }}
                                            </div>
                                        </td>
                                        <td width="50">
                                            <a href="admin/product_discount/delete/{{ $item->id }}" class="btn btn-danger btn-xs">
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