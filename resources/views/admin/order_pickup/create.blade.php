@extends('layout.admin')
@section('content-header')
    <h1> จัดซื้ออะไหล่ </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/order']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">วันที่จัดซื้อ *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            :
                                        </div>
                                        {{ Form::date('date', date('Y-m-d'), ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">หมายเหตุ</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            :
                                        </div>
                                        {{ Form::text('order_note', '', ['class' => 'form-control']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-info">
                                    <div class="box-header">
                                        <h4>รายการอะไหล่นำเข้าคลัง</h4>
                                    </div>
                                    <div class="box-body">
                                        
                                        @include('admin.order.elements.form_product')
                                        
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                
                                                <table class="table table-bordered">
                                                    
                                                    <thead>
                                                        <th>อะไหล่</th>
                                                        <th>ราคาซื้อ</th>
                                                        <th>จำนวน</th>
                                                        <th>รวมเป็นจำนวนเงิน</th>
                                                        <th>หมายเหตุ</th>
                                                        <th></th>
                                                    </thead>

                                                    <tbody id="list-item">
                                                    </tbody>

                                                </table>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    บันทึกข้อมูล
                                </button>
                                <a href="admin/order" class="btn btn-default"><< กลับ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
    </div>
@endsection
@section('script')
    <script src="js/admin/order.js"></script>
@endsection