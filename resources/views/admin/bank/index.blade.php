@extends('layout.admin')
@section('content-header')
    <h1> บัญชีธนาคาร</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">บัญชีธนาคาร</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead class="bg-primary">
                                    <th></th>
                                    <th>ข้อมูลบัญชี</th>
                                    <th>เผยแพร่</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($banks as $key => $bank)
                                    <tr>
                                        <td>
                                            <img src="{{ store_image($bank->image) }}" height="50" alt="">
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="banks" data-field="name" data-id="{{ $bank->id }}" data-reload="false" data-bank="text">
                                                {{ $bank->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="banks" data-field="status" data-id="{{ $bank->id }}" data-type="text" type="checkbox" value="1" {{ ($bank->status) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/bank/{{ $bank->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/bank/delete/{{ $bank->id }}" class="btn btn-danger btn-xs">
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