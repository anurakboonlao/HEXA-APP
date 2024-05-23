@extends('layout.admin')
@section('content-header')
    <h1> โซน</h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">โซน</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th width="50">จัดเรียง</th>
                                    <th>รหัสโซน</th>
                                    <th>ชื่อโซน</th>
                                    <th width="50">จำนวนลูกค้า</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($zones as $key => $zone)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td width="100">
                                            <div class="edit-field" contenteditable="true" data-table="zones" data-field="id" data-id="{{ $zone->id }}" data-reload="false" data-zone="text">
                                                {{ $zone->id }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="zones" data-field="name" data-id="{{ $zone->id }}" data-reload="false" data-zone="text">
                                                {{ $zone->name }}
                                            </div>
                                        </td>
                                        <td>
                                            ({{ $zone->members->count() ?? 0 }})
                                        </td>
                                        <td>
                                            <a href="admin/zone/{{ $zone->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/zone/delete/{{ $zone->id }}" class="btn btn-danger btn-xs">
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