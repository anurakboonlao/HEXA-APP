@extends('layout.admin')
@section('content-header')
    <h1> หมวดสินค้า</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">หมวดสินค้า</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead class="bg-primary">
                                    <th width="50">จัดเรียง</th>
                                    <th>ชื่อหมวดสินค้า</th>
                                    <th width="100">เผยแพร่</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                    <tr>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="product_categories" data-field="sort" data-id="{{ $category->id }}" data-reload="false" data-category="text">
                                                {{ $category->sort }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="product_categories" data-field="name" data-id="{{ $category->id }}" data-reload="false" data-category="text">
                                                {{ $category->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="product_categories" data-field="public" data-id="{{ $category->id }}" data-type="text" type="checkbox" value="1" {{ ($category->public) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/category/{{ $category->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/category/delete/{{ $category->id }}" class="btn btn-danger btn-xs">
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