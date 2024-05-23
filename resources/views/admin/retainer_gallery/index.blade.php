@extends('layout.admin')
@section('content-header')
    <h1> RetainerGallery </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">โปรโมชั่น </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <th width="50">#</th>
                                    <th>sort</th>
                                    <th width="100">รูปภาพ</th>
                                    <th>ชื่อ</th>
                                    <th>ลิ้งนอก</th>
                                    <th>เผยแพร่</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($retainer_galleries as $key => $gallery)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="retainer_galleries" data-field="sort" data-id="{{ $gallery->id }}" data-reload="false" data-gallery="text">
                                                {{ $gallery->sort }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <img width="80" src="{{ store_image($gallery->image) }}" style="max-height:100px;" alt="">
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="retainer_galleries" data-field="text" data-id="{{ $gallery->id }}" data-reload="false" data-gallery="text">
                                                {{ $gallery->name }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $gallery->url }}
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="retainer_galleries" data-field="public" data-id="{{ $gallery->id }}" data-type="text" type="checkbox" value="1" {{ ($gallery->public) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/retainer_gallery/{{ $gallery->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/retainer_gallery/delete/{{ $gallery->id }}" class="btn btn-danger btn-xs">
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