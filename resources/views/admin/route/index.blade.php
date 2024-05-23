@extends('layout.admin')
@section('content-header')
    <h1> สายรถ</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">สายรถ</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>ลำดับที่</th>
                                    <th>ชื่อสายรถ</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($routes as $key => $route)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="routes" data-field="name" data-id="{{ $route->id }}" data-reload="false" data-type="text">
                                                {{ $route->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="admin/route/{{ $route->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/route/delete/{{ $route->id }}" class="btn btn-danger btn-xs">
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