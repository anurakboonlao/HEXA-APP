<div class="scroll-box scroll-pick1"> 
    @foreach ( $order_pickups as $key => $order_pickup) 
    <div class="bg-pick1">
        <p class="pickup_address" data-address="{{ $order_pickup['address'] }}">{{ $order_pickup['address'] }}</p>
        <div class="row">
            <div class="col-lg-6 col-8">
                <p class="pickup_date" data-date="{{ date('d/m/Y', strtotime($order_pickup['created_at'] ))}}">Date {{ date('d/m/Y', strtotime($order_pickup['created_at'] ))}}</p>
                <p class="pickup_time" data-time="{{ $order_pickup['time'] }}">Time {{ $order_pickup['time'] }}</p>
            </div>
            <div class="col-lg-6 col-4">
                <button class="btn-pick2 pickup-cancel" type="button" data-id="{{$order_pickup['id']}}">ยกเลิก</button>
            </div>
        </div>
    </div>
    @endforeach
</div>