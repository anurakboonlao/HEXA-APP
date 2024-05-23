@extends('layout.admin')
@section('content-header')
    <h1> แก้ไข Voucher - {{ $voucher->name }} </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-6">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/voucher/'. $voucher->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อ Voucher *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('name', $voucher->name, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อัตราแลกเปลี่ยนสำหรับพ้อย</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('exchange_rate', $voucher->exchange_rate, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">มูลค่าของรางวัลต่อพ้อย  <span class="text-blue">เช่น 100 พ้อยแลกได้ 1 บาท</span></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('voucher_value', $voucher->voucher_value, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for=""> เงื่อนไขในการรับรางวัล </label>
                                    {{ Form::textarea('voucher_condition', $voucher->voucher_condition, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for=""> รายละเอียด </label>
                                    {{ Form::textarea('description', $voucher->description, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 5px;">
                                <div class="form-group">
                                    <label for="">รูป Voucher *</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="image" data-input="thumbnail-image" data-preview="holde-image" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> รูปแบนเนอร์ Voucher 600*800 pixcel
                                            </a>
                                        </span>
                                        {{ Form::text('image', $voucher->image, ['class' => 'form-control', 'id' => 'thumbnail-image']) }}
                                    </div>
                                    <img id="holde-image" src="{{ store_image($voucher->image) }}" style="margin-top:15px;max-height:100px;"><br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {{ Form::checkbox('public', '1', $voucher->public) }}
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
    <div class="col-xs-6">
        <div class="row">
            <div class="col-md-12">
                {{ Form::open(['files' => true, 'url' => 'admin/voucher_option']) }}
                <div class="box">
                    <div class="box-header with-border">
                        <h4>ตัวเลือกพิเศษ</h4>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">มูลค่าที่แลกได้ *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::hidden('voucher_id', $voucher->id) }}
                                        {{ Form::number('redeem_point', '', ['class' => 'form-control', 'required']) }}
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
                    {{ Form::close() }}
                    <hr>
                    <div class="box-body">
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>มูลค่าที่แลกได้</th>
                                <th>ใชพ้อยจำนวน</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($voucher->options ?? [] as $key => $item)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="voucher_options" data-field="redeem_point" data-id="{{ $item->id }}" data-reload="false" data-item="text">
                                                {{ $item->redeem_point }}
                                            </div>
                                        </td>
                                        <td align="right">
                                            {{ ($item->redeem_point * $voucher->exchange_rate) }}
                                        </td>
                                        <td width="50">
                                            <a href="admin/voucher_option/delete/{{ $item->id }}" class="btn btn-danger btn-xs">
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