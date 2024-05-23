@extends('layout.front')
@section('content')
<div class="container body">
    <div class="main_container">
        @include('front.elements.clinic_sidebar')
        <div class="right_col" role="main" style="min-height: 1057px;">
			<div class="tab-content">
                <div class="tab-pane active">
                    <div id="Profile" class="tab-pane">
                        <div class="font-price1">หน้าแรก >&nbsp;<span>My Profile</span></div>
                        <div class="bg-price1"><h2>My Profile</h2></div>
                        <div class="bg-profile2">
                            {{ Form::open(['files' => true, 'url' => 'front/member/profile/'.$member->id, 'class' => 'mg-profile1', 'method' => 'POST']) }}
                                <div class="row text-left">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="bg-profile1">Main Information</div>
                                        <div class="text-center">
                                            <label for="fileToUpload">
                                                <div class="profile-pic">
                                                    @if(!$member->image)
                                                        <img src="template/front/images/user_no_image.png" class="bg-profi1">
                                                    @else
                                                        <img src="uploads/members/thumbnail/{{ $member->image }}" class="bg-profi1">
                                                    @endif
                                                    <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                                    <span>Change Image</span>
                                                    <div class="camera-pro"><i class="fa fa-camera size-icon2" aria-hidden="true"></i></div>
                                                </div>
                                            </label>
                                            <input type="File" name="image" id="fileToUpload">
                                        </div>
                                        <label class="font-pass1">ชื่อ</label>
                                        {{ Form::text('username', $member->username, ['class' => 'form-control']) }} 
                                        <label class="font-pass1">อีเมล</label>
                                        {{ Form::email('name', $member->email, ['class' => 'form-control']) }} 
                                        <label class="font-pass1">เบอร์โทรศัพท์</label>
                                        {{ Form::text('phone', $member->phone, ['class' => 'form-control']) }} 
                                        <label class="font-pass1">ชื่อสถานที่/สังกัด</label>
                                        {{ Form::text('name', $member->name, ['class' => 'form-control']) }} 					
                                        <label class="font-pass1">ที่อยู่</label>
                                        {{ Form::text('address', $member->address, ['class' => 'form-control']) }} 
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="bg-profile1">Setting</div>
                                        <div class="line-profi1"><i class="fa fa-bell" aria-hidden="true"></i>Notification
                                            <ul class="tg-list rig-noti1">
                                                <li class="tg-list-item">
                                                    <input type="checkbox" name="notification" class="tgl tgl-light" id="noti" value="{{ $member->notification }}" {{ ($member->notification == 1) ? 'checked' : '' }}>
                                                    <label class="tgl-btn" for="noti"></label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12 pull-left">
                                    <button type="submit" class="btn-pass1">Submit</button>	
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='{{ url("/") }}/template/front/js/jquery.min.js'></script>
<script>
    $('#noti').click(function(){
        if($(this).prop("checked") == true){
            $('#noti').val(1).html();
        }
        else if($(this).prop("checked") == false){
            $('#noti').val(0).html();
        }
    });
</script>
@endsection