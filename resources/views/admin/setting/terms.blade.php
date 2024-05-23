@extends('layout.admin')
@section('content-header')
    <h1> ตั้งค่าข้อมูลทั่วไป - Terms</h1>
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
                                    <label for="">Terms *</label>
                                    {{ Form::textarea('terms', $setting->terms, ['class' => 'form-control', 'required']) }}
                                </div>
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