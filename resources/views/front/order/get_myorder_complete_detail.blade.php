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
            <p>{{ $data['entry'] }}</p>
            <label>Finish</label>
            <p>{{ $data['finish'] }}</p>
            
            <div class="pd-popup6">
                <label>Price</label>
                <p><span class="te-pri">{{ $data['price'] }}</span></p>					
            </div>
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
            
            <div class="font-popup1">
                <label>ช่องทางการจัดส่ง</label>
                @if(!$data['link_tracking'])
                    <a title="ยังไม่มีลิ้งค์ tracking" class="btn-comple3 link-tracking">ติดตามการจัดส่ง</a>
                @else
                    <a href="{{ $data['link_tracking'] }}" target="__blank" class="btn-comple3 link-tracking">ติดตามการจัดส่ง</a>
                @endif
                <p>{{ $data['shipping_company']}} : {{ $data['tracking_number'] }}</p>
                {{ Form::open(['files' => true, 'url' => 'front/order/comment', 'method' => 'POST']) }}
                <div class="mg-comple1">
                    <label>คะแนนความพึงพอใจ</label>
                    <fieldset>
                        <span class="star-cb-group">
                            <input type="radio" id="rating-5" name="rating" value="5" {{ (!$data['comment']) ? '' : ($data['comment']->rate == 5 ? 'checked' : '') }} /><label for="rating-5">5</label>
                            <input type="radio" id="rating-4" name="rating" value="4" {{ (!$data['comment']) ? '' : ($data['comment']->rate == 4 ? 'checked' : '') }} /><label for="rating-4">4</label>
                            <input type="radio" id="rating-3" name="rating" value="3" {{ (!$data['comment']) ? '' : ($data['comment']->rate == 3 ? 'checked' : '') }}/><label for="rating-3">3</label>
                            <input type="radio" id="rating-2" name="rating" value="2" {{ (!$data['comment']) ? '' : ($data['comment']->rate == 2 ? 'checked' : '') }}/><label for="rating-2">2</label>
                            <input type="radio" id="rating-1" name="rating" value="1" {{ (!$data['comment']) ? '' : ($data['comment']->rate == 1 ? 'checked' : '') }}/><label for="rating-1">1</label>
                        </span>
                    </fieldset>
                    {!! Form::textarea('message', (!$data['comment']) ? '' : $data['comment']->message , ['id' => 'input-message', 'placeholder' => 'ข้อเสนอแนะ']) !!}
                    {{ Form::hidden('member_id', request()->member->id, ['readonly'])}}
                    {{ Form::hidden('order_id', $data['id'], ['readonly'])}}
                    <div class="text-right">
                        <button type="submit" class="btn-comple1 btn-comple2" data-dismiss="modal">Cancel</button>
                        <button class="btn-comple1">Submit</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>  
        </div>
    </div>
</div>

<script  src="{{ asset("template/front/js/star/script.js") }}"></script>