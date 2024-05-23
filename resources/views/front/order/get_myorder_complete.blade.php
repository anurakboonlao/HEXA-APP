<div class="scroll-box">
    @foreach($data as $key => $myorder_complete)
    <div class="bg-den-tab2 font-order1">
        <div class="row">
            @if($member->type == 'clinic')
                <div class="col-md-4">
                    <label>Dentist</label>
                    <p>{{ $myorder_complete['doc_name'] }}</p>
                </div>
            @else
                <div class="col-md-4">
                    <label>Clinic/Hospital</label>
                    <p>{{ $myorder_complete['cus_name'] }}</p>
                </div>
            @endif
            <div class="col-md-4">
                <label>Patient</label>
                <p>{{ $myorder_complete['patient_name'] }}</p>
            </div>
            <div class="col-md-4">
                <button class="btn-order1 complete-detail" type="button" order-id="{{ $myorder_complete['id'] }}">รายละเอียด</button>
                <label>Finish</label>
                <p class="text-or3">{{ $myorder_complete['finish_date'] }}</p>
            </div>
            <div class="col-md-12">
                <label>Type of work</label>
                <p class="text-or1">{{ $myorder_complete['type'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    //Load modal my order inprocess = order_id
    $( "#myorder_complete .scroll-bar-wrap .complete-detail" ).on('click', function() {
        var order_id = $(this).attr('order-id');
        var member_id = $('.get-member-id').val();
        var order_status = 'complete';
        //get my order detail append to modal detail
        $.get('front/order/detail', { order_id: order_id, member_id: member_id, order_status: order_status }, function(res) {
            $('.order-detail-complete .modal-body').html(res);
            $('.order-detail-complete .modal-body .job').last().addClass('end');
            $('.order-detail-complete').modal('show');
        })
    });
</script>