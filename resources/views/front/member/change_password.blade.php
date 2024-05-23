@extends('layout.front')
@section('content')
<div class="container body">
    <div class="main_container">
        @include('front.elements.clinic_sidebar')
        <div class="right_col" role="main" style="min-height: 1057px;">
			<div class="tab-content">
                <div class="tab-pane active">
                    <!--Change Password-->
                    <div id="Password" class="tab-pane">
                        <div class="font-price1">หน้าแรก >&nbsp;<span>Change Password</span></div>
                        <div class="bg-price1"><h2>Change Password</h2></div>
                        <div class="bg-profile2">
                        {{ Form::open(['files' => true, 'url' => 'front/member/change_password/'.request()->member->id, 'class' => 'mg-profile1', 'method' => 'POST']) }}
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-xs-12">
                                    <label class="font-pass1  text-left">Old Password</label>
                                    <div class="password-field">
                                        {{ Form::password('old_password', ['class' => 'form-control', 'placeholder' => 'Old Password', 'required']) }} 
                                        <input class="clear" type="text" placeholder="Old Password">
                                        <button type="button">
                                            <svg viewBox="0 0 21 21">
                                                <circle class="eye" cx="10.5" cy="10.5" r="2.25" />
                                                <path class="top" d="M2 10.5C2 10.5 6.43686 5.5 10.5 5.5C14.5631 5.5 19 10.5 19 10.5" />
                                                <path class="bottom" d="M2 10.5C2 10.5 6.43686 15.5 10.5 15.5C14.5631 15.5 19 10.5 19 10.5" />
                                                <g class="lashes">
                                                    <path d="M10.5 15.5V18" />
                                                    <path d="M14.5 14.5L15.25 17" />
                                                    <path d="M6.5 14.5L5.75 17" />
                                                    <path d="M3.5 12.5L2 15" />
                                                    <path d="M17.5 12.5L19 15" />
                                                </g>
                                            </svg>
                                        </button>
                                    </div>

                                    <label class="font-pass1  text-left">New Password</label>							
                                    <div class="password-field">
                                        {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'New Password', 'required']) }} 
                                        <input class="clear" type="text" placeholder="New Password">
                                        <button type="button">
                                            <svg viewBox="0 0 21 21">
                                                <circle class="eye" cx="10.5" cy="10.5" r="2.25" />
                                                <path class="top" d="M2 10.5C2 10.5 6.43686 5.5 10.5 5.5C14.5631 5.5 19 10.5 19 10.5" />
                                                <path class="bottom" d="M2 10.5C2 10.5 6.43686 15.5 10.5 15.5C14.5631 15.5 19 10.5 19 10.5" />
                                                <g class="lashes">
                                                    <path d="M10.5 15.5V18" />
                                                    <path d="M14.5 14.5L15.25 17" />
                                                    <path d="M6.5 14.5L5.75 17" />
                                                    <path d="M3.5 12.5L2 15" />
                                                    <path d="M17.5 12.5L19 15" />
                                                </g>
                                            </svg>
                                        </button>
                                    </div>

                                    <label class="font-pass1 text-left">Comfirm Password</label>							
                                    <div class="password-field">
                                        {{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => 'Comfirm Password', 'required']) }} 
                                        <input class="clear" type="text" placeholder="Comfirm Password">
                                        <button type="button">
                                            <svg viewBox="0 0 21 21">
                                                <circle class="eye" cx="10.5" cy="10.5" r="2.25" />
                                                <path class="top" d="M2 10.5C2 10.5 6.43686 5.5 10.5 5.5C14.5631 5.5 19 10.5 19 10.5" />
                                                <path class="bottom" d="M2 10.5C2 10.5 6.43686 15.5 10.5 15.5C14.5631 15.5 19 10.5 19 10.5" />
                                                <g class="lashes">
                                                    <path d="M10.5 15.5V18" />
                                                    <path d="M14.5 14.5L15.25 17" />
                                                    <path d="M6.5 14.5L5.75 17" />
                                                    <path d="M3.5 12.5L2 15" />
                                                    <path d="M17.5 12.5L19 15" />
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn-pass1" data-toggle="modal" data-target="#myModal2">Submit</button>	

                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="myModal2" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content text-center font-alert1">
                                    <div class="modal-header pd-alert2">
                                        <h4 class="modal-title">แจ้งเตือน</h4>
                                    </div>
                                    <div class="modal-body pd-alert1">
                                        <p>ยืนยันการเปลี่ยนรหัสผ่าน?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href=""><button type="button" class="btn-alert1">OK</button></a>
                                        <button type="button" class="btn-alert1 btn-alert2" data-dismiss="modal">CANCEL</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection