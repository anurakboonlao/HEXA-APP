@extends('layout.admin_login')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="javascript:void(0);"><b>Reset password</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            @include('admin.elements.errors_validate')            
            <p class="login-box-msg">Send new password to my email.</p>
            {{ Form::open(['']) }}
            <div class="form-group has-feedback">
                <input name="email" type="email" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12 text-right">
                    <a href="admin" class="btn btn-default" role="btn">Sign in </a>
                    <button type="submit" class="btn btn-primary btn-flat">Reset password</button>
                </div>
                <!-- /.col -->
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.login-box-body -->
    </div>
@endsection