@extends('layout.admin')
@section('content-header')
    <h1> โปรไฟล์ </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            @include('admin.elements.errors_validate')
            {{ Form::open(['files' => true]) }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อีเมล *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        {{ Form::text('email', $user->email, ['class' => 'form-control', 'disabled']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อ *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">[บันทึกข้อมูล]</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รูปภาพประจำตัว 300*300 pixcel</label>
                                    {{ Form::file('image', ['class' => 'form-control']) }}
                                </div>
                                <hr>
                                <img id="profile-image" src="uploads/users/thumbnail/{{ $user->image }}" alt="" height="300">
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection