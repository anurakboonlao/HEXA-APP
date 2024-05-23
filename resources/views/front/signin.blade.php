@extends('layout.front_login')
@section('content')
<div class="container pd-login5">
	<div class="ct-login1">
		<div class="tab-content">
			<div class="font-login1 tab-pane active" role="tabpanel" id="orders1">
			{{ Form::open(['files' => true, 'url' => 'front/login', 'method' => 'POST']) }}
				{{ csrf_field() }}
				<div class="row bg-login2">
					<div class="col-lg-7 col-12 pd-0">
						<div class="heroSlider-fixed">
							<div class="overlay"></div>
							<!-- Slider -->
							<div class="slider responsive">
							@foreach($retainer_galleries as $gallery)
								<div>
									@if(!$gallery->url)
										<img src="{{ store_image($gallery->image) }}" alt="" class="">
									@else
										<a href="{{ $gallery->url }}">
											<img src="{{ store_image($gallery->image) }}" alt="" class="">
										</a>
									@endif
								</div>
							@endforeach
							</div>
							<!-- control arrows -->
							<div class="prev"><span class="icon-chevron-left"></span></div>
							<div class="next"><span class="icon-chevron-right"></span></div>
						</div>
					</div>
					<div class="col-lg-5 col-12 pd-login4">
						<div class="pd-login1">
							<form>
								<div class="form-group">
									<div class="input-group">
										<span class="icon-user input-group-addon bg-login3"></span>	
										<input  name="username" placeholder="ชื่อเข้าใช้ระบบ" class="form-control bor-login1"  type="text">
									</div>
								</div>
								<div class="form-group"> 
									<div class="input-group">
										<span class="icon-lock input-group-addon bg-login3"></span>	
										<input  name="password" placeholder="รหัสผ่าน" class="form-control bor-login1"  type="password">
									</div>
								</div>	

								<a data-toggle="modal" data-target="#reset_password">
									<span class="font-login2">ลืมรหัสผ่าน</span>
								</a>
								<button type="submit" class="btn-login1">ล็อกอิน</button>
								<div class="line-lo2"><span>หรือ</span></div><hr class="line-login1">
								<a href="front/login/facebook" type="button" class="btn-login1 btn-face"><img src="template/front/images/login/facebook.svg" class="left-log">เข้าสู่ระบบด้วย Facebook</a>
								<a href="front/login/google" type="button" class="btn-login1 btn-google"><img src="template/front/images/login/google.png" width="20px" class="left-log">เข้าสู่ระบบด้วย Google</a>
							</form>
							<p>By signing in you agree with the Terms of Service<br>and Privacy policy</p>
						</div>
					</div>
				</div>
			{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

<!-- Modal Reward Exchange Successfully -->
<div class="modal modal-reset-password" id="reset_password" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	{{ Form::open(['files' => true, 'url' => 'front/forgotpassword', 'method' => 'POST']) }}
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="font-suc1">
                    <h3>รีเซ็ตรหัสผ่านของคุณ</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-excha1 font-suc2">
                <h4 class="text-center">กรอกอีเมลที่คุณได้ลงทะเบียนไว้ เพื่อรับรหัสผ่านใหม่ !!</h4>
                <div class="form-group">
                    <div class="input-group">
                        <span class="icon-user input-group-addon bg-login3"></span>	
                        <input name="email" placeholder="Email" class="form-control bor-login1"  type="email">
                    </div>
                </div>
                <button class="btn-login1 btn-face mb-5" type="submit">ยืนยันการรีเซ็ตรหัสผ่าน</button>
            </div>
        </div>
    </div>
	{{ Form::close() }}
</div>
<!-- Modal Reward Exchange Successfully -->
@endsection