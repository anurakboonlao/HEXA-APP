@extends('layout.admin')
@section('content-header')
    <h1> โปรโมชั่น </h1>
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
                                    <th>แสดงบน</th>
                                    <th width="100">รูปภาพ</th>
                                    <th>ข้อความบรรทัดที่ 1</th>
                                    <th>ข้อความบรรทัดที่ 2</th>
                                    <th>ลิ้งนอก</th>
                                    <th>เผยแพร่</th>
                                    <th width="50"></th>
                                </thead>
                                <tbody>
                                    @foreach ($promotions as $key => $promotion)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="promotions" data-field="sort" data-id="{{ $promotion->id }}" data-reload="false" data-promotion="text">
                                                {{ $promotion->sort }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ type_promotion($promotion->type) }}
                                        </td>
                                        <td class="text-center">
                                            <img width="80" src="{{ store_image($promotion->image) }}" style="max-height:100px;" alt="">
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="promotions" data-field="text" data-id="{{ $promotion->id }}" data-reload="false" data-promotion="text">
                                                {{ $promotion->text }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="edit-field" contenteditable="true" data-table="promotions" data-field="small_text" data-id="{{ $promotion->id }}" data-reload="false" data-promotion="text">
                                                {{ $promotion->small_text }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $promotion->url }}
                                        </td>
                                        <td>
                                            <input class="checkbox-auto-update minimal" data-table="promotions" data-field="public" data-id="{{ $promotion->id }}" data-type="text" type="checkbox" value="1" {{ ($promotion->public) ? "checked" : "" }}>
                                        </td>
                                        <td>
                                            <a href="admin/promotion/{{ $promotion->id }}/edit" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="admin/promotion/delete/{{ $promotion->id }}" class="btn btn-danger btn-xs">
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