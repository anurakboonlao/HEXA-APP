@extends('layout.admin')
@section('content-header')
    <h1> ข้อมูลสินค้า</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">ข้อมูลสินค้า</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>#</th>
                                    <th>รูปสินค้า</th>
                                    <th>หมวดสินค้า</th>
                                    <th>สินค้า</th>
                                    <th>ราคา</th>
                                    <th width="80">สินค้าแนะนำ</th>
                                    <th width="100">เผยแพร่</th>
                                    <th width="30" class=""></th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <img src="{{ store_image($product->image) }}" width="50">
                                        </td>
                                        <td>
                                            {{ $product->category->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $product->name }}
                                        </td>
                                        <td align="right">
                                            {{ number_format($product->price) }}
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="products" data-field="recommended" data-id="{{ $product->id }}" data-type="text" type="checkbox" value="1" {{ ($product->recommended) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="products" data-field="public" data-id="{{ $product->id }}" data-type="text" type="checkbox" value="1" {{ ($product->public) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/product/{{ $product->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/product/delete/{{ $product->id }}" class="btn btn-danger btn-xs">
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