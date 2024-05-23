<div class="modal-header">
    <div class="font-denti1">
        <h3>{{ $voucher['name'] }}</h3>
    </div>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body pd-rewa6">
    <div class="row">
        <div class="col-md-6">
            <div class="line-rewa4 pd-rewa7">
                <img src="{{ $voucher['image'] }}" width="100%">
                <p>ทุก 25 บาท = 1 คะแนน<br>ทุก 1,000 คะแนน = 100 บาท</p>
            </div>
        </div>
        <div class="col-md-6 font-rewa2">
            <p class="point6">ข้อความ + เงื่อนไข<br><br> {{ $voucher['description'] }} <br> {{ $voucher['voucher_condition'] }} </p>
            <span>ระบุมูลค่าของรางวัลและคะแนนที่ต้องการแลก</span>
            {{ Form::open(['url' => 'front/reward/add_cart', 'id' => 'form_reward_exchange', 'method' => 'POST']) }}
                <label>มูลค่าของรางวัล</label>               
                {{ Form::number('amount', '', ['class' => 'form-rewa1 input-amount', 'placeholder' => '0', 'min' => $voucher['voucher_option_limit'], 'required'])}}
                <label>คะแนนที่ใช้แลก</label>
                {{ Form::text('point', '', ['class' => 'form-rewa1 input-point', 'placeholder' => '0', 'readonly'])}}
                {{ Form::hidden('exchange_rate', $voucher['exchange_rate'], ['class' => 'input-exchange-rate', 'readonly'])}}
                {{ Form::hidden('voucher_id', $voucher['id'], ['readonly'])}}
                {{ Form::hidden('member_id', request()->member->id, ['readonly'])}}
                {{ Form::hidden('voucher_name', $voucher['name'], ['readonly'])}}
                <span class="alert-point-limit hide">*ขออภัยค่ะ คะแนนไม่เพียงพอสำหรับแลกของรางวัล</span>
                <div class="mg-rewa2">
                    <button class="btn-rewa4" type="submit">ใส่ตะกร้าของรางวัล</button>
                    <button class="btn-rewa4 btn-rewa6 reward-ex" type="button" data-dismiss="modal">แลกคะแนน</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script src="{{ asset("template/front/js/reward/reward_detail.js") }}"></script>