@extends('layout.admin')
@section('content-header')
    <h1> ค่าใช้จ่าย</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">ค่าใช้จ่าย</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>ลำดับที่</th>
                                    <th>ประเภท</th>
                                    <th>ชื่อค่าใช้จ่าย</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($expenes as $key => $expene)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            {{ ($expene->type == '1') ? "ค่าใช้จ่ายพื้นฐาน" : "ค่าใช้จ่ายเสริม" }}
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="expenes" data-field="name" data-id="{{ $expene->id }}" data-reload="false" data-type="text">
                                                {{ $expene->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="admin/expene/{{ $expene->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/expene/delete/{{ $expene->id }}" class="btn btn-danger btn-xs">
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