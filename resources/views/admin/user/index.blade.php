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
                                    <th>รหัสพนักงาน</th>
                                    <th>ระดับผู้ใช้</th>
                                    <th>ชื่อพนักงาน</th>
                                    <th></th>
                                    <th width="60"></th>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                    <tr>
                                        <td> {{ $user->id }} </td>
                                        <td> {{ role_user($user->role) }} </td>
                                        <td> {{ $user->name }} </td>
                                        <th>
                                            <img height="64" src="{{ store_image($user->image) }}" class="img-circle" alt="User Image">
                                        </th>
                                        <td>
                                            <a href="admin/user/update/{{ $user->id }}" class="btn btn-default btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/user/delete/{{ $user->id }}" class="btn btn-danger btn-sm">
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