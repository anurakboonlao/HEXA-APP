<div class="row">
    <div class="col-md-6 line-popup1 pd-popup4">
        <div class="pd-popup1 font-popup1">
            <label>Code</label>
            <p>{{ $data['code'] }}</p>
            <label>Customer</label>
            <p>{{ $data['cus_name'] }}</p>
            <label>Dentist</label>
            <p>{{ $data['doc_name'] }}</p>
            <label>Patient</label>
            <p>{{ $data['patient_name'] }}</p>
            <div class="pd-popup2">
                <label>Product</label>
                @foreach($data['products'] as $product)
                <p class="ri-pop1">
                    <label class="list-product" title="{{ $product['pord_name'] }}">- {{ $product['pord_name'] }}</label>
                    <span>{{ $product['qty'] }} {{ $product['uom_name'] }}</span><br>
                </p>
                @endforeach
            </div>
            <label>Entry</label>
            <p>{{  $data['entry'] }}</p>
            <label>Finish</label>
            <p>{{ $data['finish'] }}</p>
        </div>
    </div>
    <div class="col-md-6 pd-popup3">
        <div class="pd-popup5">
            @foreach($data['status'] as $status)
            <div class="job">
                <div class="date"><span class="cir-time1"></span></div>  
                <div class="job_description ri-pop1">
                    {{ $status['name'] }}<span class="text-popup1">Done</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>