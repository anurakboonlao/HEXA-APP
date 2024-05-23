@extends('layout.admin')
@section('content-header')
    <h1> แก้ไขข้อมูล โซน </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-6">
    @include('admin.elements.errors_validate')
    {{ Form::open(['files' => true, 'url' => 'admin/zone/' . $zone->id, 'method' => 'PUT']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">ชื่อโซน *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-navicon"></i>
                                        </div>
                                        {{ Form::text('name', $zone->name, ['class' => 'form-control', 'required']) }} 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="">รายละเอียด *</label>
                                    {{ Form::textarea('desctiption', $zone->desctiption, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ (\URL::previous()) ? \URL::previous() : 'admin/zone' }}" class="btn btn-default"><< กลับ</a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-floppy-o"></i>
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
    </div>
    <div class="col-xs-6">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::select('member_id', [], '', ['class' => 'form-contro select2', 'style' => 'width: 90%;']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" id="table-members">
                                    <thead>
                                        <th>#</th>
                                        <th>ชื่อ</th>
                                        <th>Code</th>
                                        <th>Address</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($zone->members as $member)
                                        <tr>
                                            <td></td>
                                            <td>{{ $member->member->name ?? '' }}</td>
                                            <td>{{ $member->member->customer_verify_key ?? '' }}</td>
                                            <td>{{ $member->member->address ?? '' }}</td>
                                            <td>
                                                <a href="" data-id="{{ $member->id }}" class="btn btn-warning btn-xs btn-remove-member">
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
</div>
@endsection
@section('script')
    <script>
        $(function(e) {

            $('select.select2').select2({
                ajax: {
                    url: 'api/member/dat_to_zone',
                    dataType: 'json'
                }
            });

            $('select.select2').on('select2:select', function (e) {

                var member_id = e.currentTarget.value;
                var zone_id = '{{ $zone->id }}';

                $.get('api/zone/add_member', {zone_id: zone_id, member_id: member_id}, function(res) {

                    $('#table-members tbody').prepend(res);
                });

            });

            $('#table-members').on('click', '.btn-remove-member', function(e) {
                e.preventDefault();

                var element = $(this);
                var id = $(this).attr('data-id');

                $.get('api/zone/delete_member/' + id, function(res) {
                    element.closest('tr').remove();
                });
            })
        })
    </script>
@endsection