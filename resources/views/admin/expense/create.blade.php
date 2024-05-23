@extends('layout.admin')
@section('content-header')
    <h1> ค่าใช้จ่าย </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/expense']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="radio">
                                    <label><input type="radio" value="1" name="status" checked>ค่าใช้จ่ายพื้นฐาน</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" value="2" name="status">ค่าใช้จ่ายเสริม</label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อค่าใช้จ่าย *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            :
                                        </div>
                                        {{ Form::text('name', '', ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    บันทึกข้อมูล
                                </button>
                                <a href="admin/expense" class="btn btn-default"><< กลับ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
    </div>
@endsection