@extends('layout.admin')
@section('content-header')
    <h1> นำเข้าสินค้า </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/product/import']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-success">
                                    <h5>การนำเข้าข้อมูลไม่ควรเกิน 500 รายการต่อครั้ง</h5>
                                </div>
                                <div class="alert alert-danger">
                                    <h5>** ขั้นตอนนี้อาจจะใช้เวลาในการนำเข้าสินค้า กรุณาอย่ากดปิดหน้าต่างหรือออกจากหน้านี้ถ้ายังทำรายการไม่สำเร็จ</h5>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ไฟล์ excel * <a href="template/files/product-import-termplate.xlsx"><span class="text-blue">ตัวอย่างไฟล์</span></a></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::file('file', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    นำเข้าข้อมูล
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