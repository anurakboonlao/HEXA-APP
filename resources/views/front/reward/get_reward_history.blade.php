<div class="scroll-box pd-his1 scroll-his1">
    @foreach($redemptions as $history)
    @if($history['approved'] == '0')
        <div class="bg-his1">
            <div class="row">
                <div class="col-md-4 col-6 font-his1 pd-his2"><label>วันที่ทำรายการ</label><p>{{ $history['date'] }}</p></div>
                <div class="col-md-4 col-6 font-his1 pd-his3"><label>ของรางวัล</label><p>{{ $history['voucher']['name'] }}</p></div>
                <div class="col-md-4 font-his1">
                    <ul class="ul-his1">
                        <li><label>คะแนน</label><p><span>{{ $history['point'] }}</span></p></li>
                        <li><label>มูลค่า</label><p><span>{{ $history['amount'] }}</span></p></li>
                    </ul>
                </div>
                <!--<div class="col-md-3"><div class="ct-his1"><button class="btn-his1">อยู่ระหว่างดำเนินการ</button></div></div>-->
            </div>
        </div>
    @else
        <div class="bg-his1 bg-his2">
            <div class="row">
                <div class="col-md-4 col-6 font-his1 pd-his2"><label>วันที่ทำรายการ</label><p>{{ $history['date'] }}</p></div>
                <div class="col-md-4 col-6 font-his1 pd-his3"><label>ของรางวัล</label><p>{{ $history['voucher']['name'] }}</p></div>
                <div class="col-md-4 font-his1">
                    <ul class="ul-his1">
                        <li><label>คะแนน</label><p><span>{{ $history['point'] }}</span></p></li>
                        <li><label>มูลค่า</label><p><span>{{ $history['amount'] }}</span></p></li>
                    </ul>
                </div>
                <!--<div class="col-md-3"><div class="ct-his1"><button class="btn-his1 btn-his2">จัดส่งของรางวัลเรียบร้อย</button></div></div>-->
            </div>
        </div>
    @endif
    @endforeach
</div>