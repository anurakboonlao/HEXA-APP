@extends('layout.admin')
@section('content-header')
    <h1> ค่าขนส่ง</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">ค่าขนส่ง</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th width="50">ลำดับที่</th>
                                    <th>ชื่อค่าขนส่ง</th>
                                    <th>ราคา</th>
                                    <th>การคำนวณ</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($shippings as $key => $shipping)
                                        <tr>
                                            <td>{{ ($key + 1) }}</td>
                                            <td>
                                                <div class="edit-field" contenteditable="true" data-table="shippings" data-field="name" data-id="{{ $shipping->id }}" data-reload="false" data-shipping="text">
                                                    {{ $shipping->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="edit-field" contenteditable="true" data-table="shippings" data-field="price" data-id="{{ $shipping->id }}" data-reload="false" data-shipping="text">
                                                    {{ $shipping->price }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ shipping_type($shipping->type) }}
                                            </td>
                                            <td>
                                                <a href="admin/shipping/{{ $shipping->id }}/edit" class="btn btn-default btn-xs">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="admin/shipping/delete/{{ $shipping->id }}" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection