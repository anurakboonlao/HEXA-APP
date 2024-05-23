@extends('layout.admin')
@section('content-header')
    <h1> แก้ไขข้อมูล Username และ Password ลูกค้า </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">ข้อมูลลูกค้า</h4>
            </div>
            <div class="box-body">
            @include('admin.elements.errors_validate')
            {{ Form::open(['files' => true, 'url' => 'admin/update_customer_account/'.$customer->id, 'method' => 'PUT']) }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    {{ Form::text('username', $customer->username, ['class' => 'form-control', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                @if(!$customer->password)
                                    <div class="form-group">
                                        <label for="">Password : <span class="label label-success">กำหนดรหัสผ่านให้กับ Username</span> </label>
                                        <div class="input-group">
                                            {{ Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'input_password', 'required']) }}
                                            <span class="input-group-btn">
                                                <a class="btn btn-primary toggle-show-password">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="">Password : <span class="label label-danger">เปลี่ยนรหัสผ่านใหม่ กรณีลูกค้าลืมรหัสผ่าน</span> </label>
                                        <div class="input-group">
                                            {{ Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'input_password']) }}
                                            <span class="input-group-btn">
                                                <a class="btn btn-primary toggle-show-password">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รหัสลูกค้า ( Invite Code )</label>
                                    {{ Form::text('customer_verify_key', $customer->customer_verify_key, ['class' => 'form-control', 'disabled']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ระดับการใช้งาน</label>
                                    {{ Form::text('text', (!$customer->type) ? "ทั่วไป" : type_customer($customer->type), ['class' => 'form-control', 'disabled']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อ</label>
                                    {{ Form::text('name', $customer->name, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">อีเมล</label>
                                    {{ Form::email('email', $customer->email, ['class' => 'form-control', 'disabled']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ที่อยู่</label>
                                    {{ Form::text('address', $customer->address, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">เบอร์โทรศัพท์</label>
                                    {{ Form::text('phone', $customer->phone, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-floppy-o"></i>
                                        แก้ไขข้อมูลลูกค้า
                                    </button>
                                    <a href="admin/customer" class="btn btn-default"><< กลับ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
<div>
@endsection
@section('script')
    <script>
        $('.toggle-show-password').on('click', function() {
            var input = document.getElementById("input_password");

            if (input.type === "password") {

                input.type = "text";
                $(this).find('.fa-eye-slash,.fa-eye').toggleClass('fa-eye-slash').toggleClass('fa-eye');

            } else {
                
                input.type = "password";
                $(this).find('.fa-eye,.fa-eye-slash').toggleClass('fa-eye').toggleClass('fa-eye-slash');
            }
        })
    </script>
@endsection