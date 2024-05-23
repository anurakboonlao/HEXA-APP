{{ Form::open(['url' => 'front/reward/redeem_voucher_cart', 'id' => 'form_exchange', 'method' => 'POST']) }}
<div class="row voucher_cart_area" data-type="voucher_cart">
    <div class="col-md-6">
        <div class="left-area">
            <div class="address">
                <!-- get doctor contact -->
                <img src="template/front/images/loading.gif" class="img-fluid">
            </div>
            <button class="btn-excha1 reward-new-address" type="button">
                <span class="icon-plus input-group-addon pd-excha2"></span>ที่อยู่จัดส่งใหม่
            </button>
        </div>
    </div>
    <div class="col-md-6">
        <ul class="line-excha1">
            <li>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events check is-upgraded" for="checkAll" data-upgraded=",MaterialCheckbox,MaterialRipple">
                    <input type="checkbox" id="checkAll" class="mdl-checkbox__input left-ze">
                    <span class="mdl-checkbox__label font-excha2 left-re-poin1">เลือกทั้งหมด</span>
                    <span class="mdl-checkbox__focus-helper"></span>
                    <span class="mdl-checkbox__box-outline">
                        <span class="mdl-checkbox__tick-outline"></span>
                    </span>
                    <span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">
                        <span class="mdl-ripple"></span>
                    </span>
                </label>           
            </li>
            <div class="reward-list">
                @foreach($voucher_carts as $key => $voucher_cart)
                <li class="item-list-{{ $voucher_cart['id'] }}">
                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect reward-{{ $key + 1 }} check" for="reward-{{ $key + 1 }}" data-upgraded=",MaterialCheckbox,MaterialRipple">
                        <input type="checkbox" name="voucher_cart_id[]" value="{{ $voucher_cart['id'] }}" 
                            id="reward-{{ $key + 1 }}" data-id="{{ $key + 1 }}" class="mdl-checkbox__input"
                            point="{{ $voucher_cart['point'] }}" amount="{{ $voucher_cart['voucher_value'] }}"
                            onclick="sumTotalVoucher()"
                        />
                        <div class="left-re-poin1">
                        <p>
                            <span class="pl-excha1">{{ $voucher_cart['voucher_value'] }} บาท</span>
                            <span class="wid-excha1 point1" title="{{ $voucher_cart['name'] }}">{{ $voucher_cart['name'] }}</span>
                            </p>
                        </div>
                        <span class="mdl-checkbox__focus-helper"></span>
                        <span class="mdl-checkbox__box-outline">
                            <span class="mdl-checkbox__tick-outline"></span>
                        </span>
                        <span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">
                            <span class="mdl-ripple"></span>
                        </span>
                    </label>
                    <a data-id="{{ $voucher_cart['id'] }}" type="button" class="reward-delete" title="ลบข้อมูล">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-trash ri-excha1" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                        </svg>
                    </a>
                </li>
                @endforeach
            </div>
        </ul>
        <div class="font-excha1 mg-excha1">
            <label>มูลค่าของรางวัล (บาท)</label>
            {{ Form::text('amount', '', ['class' => 'form-rewa1', 'placeholder' => '0', 'readonly', 'required']) }} 
            <label>คะแนนที่ใช้แลก (คะแนน)</label>
            {{ Form::text('point', '', ['class' => 'form-rewa1', 'placeholder' => '0', 'readonly', 'required']) }} 
            <span id="doc_reward_total" data-value="{{ $reward_point_total }}"></span>
        </div>
        <div class="text-center">
            <span class="alert-point-limit hide">*ขออภัยค่ะ คะแนนไม่เพียงพอสำหรับแลกของรางวัล</span>
        </div>
        <div class="mg-rewa2">
            <button class="btn-rewa4 btn-rewa6 float-right" type="submit">แลกคะแนน</button>
        </div>
    </div>
</div>
{{ Form::close() }}

<script src="{{ asset("template/front/js/reward/script.js") }}"></script>