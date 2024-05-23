@extends('layout.admin')
@section('content-header')
    <h1> ค่าขนส่ง </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/shipping']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อประเภท *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-truck"></i>
                                        </div>
                                        {{ Form::text('name', '', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ราคา *</label>
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
                                    <label for="">การคำนวณค่าขนส่ง *</label>
                                    {{ Form::select('type', ['all' => 'ราคารวมทุกๆชิ้น', 'one' => 'ราคาต่อชิ้น'], '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="admin/shipping" class="btn btn-default"><< กลับ</a>
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