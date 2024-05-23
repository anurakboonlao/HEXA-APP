@extends('layout.admin')
@section('content-header')
    <h1> พนักงาน</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">พนักงาน</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th>ลำดับที่</th>
                                    <th>ชื่อพนักงาน</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($staffs as $key => $staff)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="staffs" data-field="name" data-id="{{ $staff->id }}" data-reload="false" data-type="text">
                                                {{ $staff->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="admin/staff/{{ $staff->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/staff/delete/{{ $staff->id }}" class="btn btn-danger btn-xs">
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