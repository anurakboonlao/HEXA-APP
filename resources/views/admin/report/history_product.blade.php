@extends('layout.admin')
@section('content-header')
    <h1> ประวัติอะไหล่ - {{ (request()->input('type') == 'in') ? "ซื้อเข้า" : "ขายออก" }} </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {{ Form::open(['method' => 'get', 'id' => 'search']) }}
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-5 text-right">
                        {{ Form::hidden('type', request()->input('type') ?? 'in') }}
                        {{ Form::date('start_date', '', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-5 text-right">
                        {{ Form::date('end_date', '', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-2">
                        <button class="btn btn-success" type="submit">
                            Filter <i class="fa fa-search"></i>
                        </button>
                        <button class="btn btn-default export-excel">
                            <i class="fa fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">อะไหล่</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            @if (request()->input('type') == 'in')
                                @include('admin.report.elements.history_in_table')
                            @else
                                @include('admin.report.elements.history_out_table')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function(e) {
            $('.export-excel').on('click', function(e) {
                e.preventDefault();
                
                var start_date = $("input[name='start_date']").val()
                var end_date = $("input[name='end_date']").val()
                var type = $("input[name='type']").val()

                window.location = "admin/report/product_excel?type=" + type + "&start_date=" + start_date + "&end_date=" + end_date;
            })
        })
    </script>
@endsection