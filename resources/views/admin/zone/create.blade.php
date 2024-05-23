@extends('layout.admin')
@section('content-header')
    <h1> เพิ่มข้อมูล โซน </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/zone']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อโซน *</label>
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
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รายละเอียด *</label>
                                    {{ Form::textarea('desctiption', '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ (\URL::previous()) ? \URL::previous() : 'admin/zone' }}" class="btn btn-default"><< กลับ</a>
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