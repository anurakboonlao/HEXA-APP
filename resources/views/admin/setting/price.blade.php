@extends('layout.admin')
@section('content-header')
    <h1> ตั้งค่าข้อมูลทั่วไป - Price List </h1>
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ไฟล์ PDF *</label>
                                    {{ Form::file('hexa_price_list', ['class' => 'form-control', 'required']) }}
                                </div>
                                @if ($setting->hexa_price_list)
                                    <a target="_blank" href="files/settings-{{ $setting->hexa_price_list }}" class="btn btn-default btn-xs">ไฟล์ตัวอย่าง</a>
                                @endif
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