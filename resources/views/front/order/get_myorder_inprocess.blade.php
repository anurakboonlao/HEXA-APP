<div class="scroll-box">	
    @foreach($data as $key => $myorder_inprocess)
    <div class="bg-den-tab1 font-order1">
        <div class="row">
            @if($member->type == 'clinic')
                <div class="col-md-4">
                    <label>Dentist</label>
                    <p>{{ $myorder_inprocess['doc_name'] }}</p>
                </div>
            @else
                <div class="col-md-4">
                    <label>Clinic/Hospital</label>
                    <p>{{ $myorder_inprocess['cus_name'] }}</p>
                </div>
            @endif
            <div class="col-md-4">
                <label>Patient</label>
                <p>{{ $myorder_inprocess['patient_name'] }}</p>
            </div>
            <div class="col-md-4">
                <button class="btn-order1 inprocess-detail" type="button" order-id="{{ $myorder_inprocess['id'] }}">รายละเอียด</button>
                <label>Finish</label>
                <p class="text-or2">{{ $myorder_inprocess['finish_date'] }}</p>
            </div>
            <div class="col-md-12">
                <label>Type of work</label>
                <p class="text-or1">{{ $myorder_inprocess['type'] }}</p>
            </div>
        </div>
    </div>
   @endforeach
</div>

<script>
    //Load modal my order inprocess = order_id
    $( "#myorder_inprocess .scroll-bar-wrap .inprocess-detail" ).on('click', function() {
        var order_id = $(this).attr('order-id');
        var member_id = $('.get-member-id').val();
        var order_status = 'inprocess';
        //get my order detail append to modal detail
        $.get('front/order/detail', { order_id: order_id, member_id: member_id, order_status: order_status }, function(res) {
            $('.order-detail-inprocess .modal-body').html(res);
            $('.order-detail-inprocess .modal-body .job').last().addClass('end');
            $('.order-detail-inprocess').modal('show');
        })
    });
</script>
