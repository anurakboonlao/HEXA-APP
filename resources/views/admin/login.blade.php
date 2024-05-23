@extends('layout.admin_login')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><b>HEXA CERAM</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        @include('admin.elements.errors_validate')            
        <p class="login-box-msg">Sign in to start your session</p>
        {{ Form::open(['']) }}
        <div class="form-group has-feedback">
            <input name="email" type="email" class="form-control" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input name="password" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-xs-12 text-right">
                <a href="admin/reset_password" class="btn btn-warning" role="btn">Reset Password</a>
                <button type="submit" class="btn btn-primary btn-flat">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
        {{ Form::close() }}
    </div>
    <!-- /.login-box-body -->
</div>
@endsection