@extends('layout.admin')
@section('content-header')
    <h1> Voucher </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Voucher </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th width="80">Voucher ID</th>
                                    <th width="100">รูปภาพ</th>
                                    <th>Voucher</th>
                                    <th>อัตราแลกเปลี่ยน</th>
                                    <th>มูลค่าของรางวัล</th>
                                    <th>รายละเอียด</th>
                                    <th>เผยแพร่</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($vouchers as $key => $voucher)
                                    <tr>
                                        <td class="text-center">{{ $voucher->id }}</td>
                                        <td class="text-center">
                                            <img width="80" src="{{ store_image($voucher->image) }}" style="max-height:100px;" alt="">
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="vouchers" data-field="name" data-id="{{ $voucher->id }}" data-reload="false" data-voucher="text">
                                                {{ $voucher->name }}
                                            </div>
                                        </td>
                                        <td class="text-right">{{ $voucher->exchange_rate }}</td>
                                        <td class="text-right">{{ $voucher->voucher_value }}</td>
                                        <td>{{ $voucher->description }}</td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="vouchers" data-field="public" data-id="{{ $voucher->id }}" data-type="text" type="checkbox" value="1" {{ ($voucher->public) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/voucher/{{ $voucher->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/voucher/delete/{{ $voucher->id }}" class="btn btn-danger btn-xs">
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