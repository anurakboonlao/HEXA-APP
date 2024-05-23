@extends('layout.admin')
@section('content-header')
    <h1> VoucherBanner </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">VoucherBanner </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th width="50">#</th>
                                    <th width="100">รูปภาพ</th>
                                    <th>ชื่อแบนเนอร์</th>
                                    <th>Voucher ID</th>
                                    <th width="100">ลำดับการแสดง</th>
                                    <th width="100">เผยแพร่</th>
                                    <th width="80"></th>
                                </thead>
                                <tbody>
                                    @foreach ($voucher_banners as $key => $banner)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td class="text-center">
                                            <img width="80" src="{{ store_image($banner->image) }}" style="max-height:100px;" alt="">
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="voucher_banners" data-field="name" data-id="{{ $banner->id }}" data-reload="false" data-voucher="text">
                                                {{ $banner->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="voucher_banners" data-field="url" data-id="{{ $banner->id }}" data-reload="false" data-voucher="text">
                                                {{ $banner->url }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="edit-field" contenteditable="true" data-table="voucher_banners" data-field="sort" data-id="{{ $banner->id }}" data-reload="false" data-voucher="text">
                                                {{ $banner->sort }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <input class="checkbox-auto-update minimal" data-table="voucher_banners" data-field="public" data-id="{{ $banner->id }}" data-type="text" type="checkbox" value="1" {{ ($banner->public) ? "checked" : "" }}>
                                        </td>
                                        <td class="text-center">
                                            <a href="admin/voucher_banner/{{ $banner->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/voucher_banner/delete/{{ $banner->id }}" class="btn btn-danger btn-xs">
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