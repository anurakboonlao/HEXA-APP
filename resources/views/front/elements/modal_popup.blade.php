<!-- Modal Popup Invite Code -->
<div class="modal modal-invite-code" id="invite" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content radi-popup1">
            <div class="modal-header line-popup2 mt-2"></div>
            <div class="modal-body pd-popup7">
                {{ Form::open(['url' => 'front/invite_code?member_id='.request()->member->id, 'method' => 'post'])}}
                    {{ Form::text('invite_code', '', ['class' => 'form-modal1 text-center', 'placeholder' => 'กรุณากรอก Invite Code', 'required'])}}
                    <a href="{{ $setting->hexa_line }}" target="_blank" type="button" class="btn-popup1">ขอรับ invite Code เพื่อยืนยันตัวตน</a>
                    <dvi class="row">
                        <button class="btn-rewa4 btn-suc1 mr-2" type="submit">Submit</button>
                        <a href="front/logout" type="button" class="btn-rewa4 btn-suc1 btn-logout ml-2">Logout</a>
                    </dvi>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<!-- Modal Popup Invite Code -->

<!-- ======= REWARD VOUCHER ===========-->
<!-- Modal Reward Detail Exchange -->
<div class="modal modal-reward-detail" id="modal-reward1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!---- Get reward detail to modal reword detail ---->
        </div>
    </div>
</div>
<!-- /.Modal Reward Detail Exchange -->

<!-- Modal Reward My Cart Exchange Multiple Item -->
<div class="modal modal-reward-cart" id="modal-exchange1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-denti1">
                    <h3>แลกของรางวัล</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1">
                <!-- Append Voucher_cart & Erp Get Doctor Contact All  -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Reward My Cart Exchange -->

<!-- Modal Reward Exchange Successfully -->
<div class="modal" id="modal-success1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-suc1">
                    <h3>แลกคะแนนสำเร็จ</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 font-suc2">
                <div class="check-suc1"><span class="icon-check input-group-addon"></span></div>
                <p>ของรางวัลจะถูกจัดส่งภายใน 7 วัน</p>
                <button class="btn-rewa4 btn-suc1" data-dismiss="modal">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Reward Exchange Successfully -->

<!-- Modal Reward History -->
<div class="modal modal-reward-history" id="modal-history1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-his1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row wi-sea">
                    <div class="col-md-8 font-denti1"><h3>ประวัติการแลกคะแนน</h3></div>
                    <!--<div class="col-md-4"><input type="text" class="main-input main-name mg-his1 search-history" name="key"  placeholder="Search" onfocus="clearText(this)" onblur="replaceText(this)"></div>-->
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="scroll-bar-wrap">
                    <!-- Load Reward History Data -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Reward History -->
<!-- ========== REWARD VOUCHER ===========-->
    
<!-- ============== ORDER PICKUP ================ -->
<!-- Modal My Order In Process-->
<div class="modal order-detail-inprocess" id="modal-process1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-denti1">
                    <h3>In Process</h3>&nbsp;
                    <p>อยู่ระหว่างขั้นตอนการผลิต</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- import /front/order/get_myorder_inprocess_detail -->
            </div>
        </div>
    </div>
</div>
<!-- /.Modal My Order In Process-->

<!-- Modal My Order Complete-->
<div class="modal order-detail-complete" id="modal-complete1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-denti1">
                    <h3>Complete</h3>&nbsp;
                    <p>ผลิตเสร็จสมบูรณ์ พร้อมส่ง</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- import /front/order/get_myorder_complete_detail -->
            </div>
        </div>
    </div>
</div>
<!-- /.Modal My Order Complete-->	

<!-- Modal Thank You / Feedback Commment & Rating -->
<div class="modal order-add-comment-thank" id="modal-thank1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 font-thank1">
                <p>ขอขอบพระคุณคุณหมอที่ให้คำแนะนำ <br> บริษัทฯจะนำไปพัฒนาการทำงานให้ดียิ่งขึ้น</p>
                <button class="btn-rewa4 btn-suc1 mb-3" data-dismiss="modal">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Thank You / Feedback Commment & Rating -->

<!-- Modal Comfirm Order Pickup -->
<div class="modal" id="modal-get-job1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-pick2">
                    <h3>ยืนยันการรับงาน</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 text-center font-pick3">
                <p>คลินิก <span id="locate"></span></p>
                <ul class="ul-his1 pd-pick1">
                    <li>Date <span id="date"></span></li>
                    <li>Time <span id="time"></span></p></li>
                </ul>
                <div class="mg-pick1">
                    <button type="button" class="btn-comple1 btn-comple2" data-dismiss="modal">Cancel</button>
                    <button id="submit" class="btn-comple1">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Comfirm Order Pickup -->

<!-- Modal Cancel Order Pickup -->
<div class="modal modal-order-pickup" id="modal-pickup" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-suc1" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-pick2 font-pick4">
                    <h3>ยกเลิกการรับงาน</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 text-center font-pick3 font-pick4">
                <p class="clinic_name">คลินิก <span>XXXXX</span></p>
                <ul class="ul-his1 pd-pick1 mt-2">
                    <li class="pickup_date">Date <span>XXXXX</span></li>
                    <li class="pickup_time">Time <span>XXXXX</span></li>
                </ul>
                <div class="mg-pick1">
                    <button type="button" class="btn-comple1 btn-comple2" data-dismiss="modal">Cancel</button>
                    <a href="" class="pickup-delete">
                        <button class="btn-comple1">Submit</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.Modal Cancel Order Pickup -->
<!-- /.=========== ORDER PICKUP ============ -->

