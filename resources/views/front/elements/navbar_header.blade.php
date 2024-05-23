<div class="site-mobile-menu site-navbar-target">
	<div class="site-mobile-menu-header">
		<div class="site-mobile-menu-close mt-3">
			<span class="icon-close2 js-menu-toggle"></span>
		</div>
	</div>
	<div class="site-mobile-menu-body"></div>
</div>

<header class="site-navbar js-sticky-header site-navbar-target" role="banner">
	<div class="container-fluid pd-denti3">
		<div class="row align-items-center">
			<div class="col-6 col-xl-2">
				<a href="front/dashboard"><img src="template/front/images/dentist/logo.png"></a>
			</div>
			<div class="col-12 col-md-10 d-none d-xl-block">
				<nav class="site-navigation position-relative text-right" role="navigation">
					<ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
						<li>
						@if(!$setting->hexa_retainer_gallery)
							<a class="nav-link">&nbsp;&nbsp;<i class="fas fa-list-ul mg-nav1 font-20" aria-hidden="true"></i>&nbsp;&nbsp;RetainerGallery</a>
						@else
							<a href="files/settings-{{ $setting->hexa_retainer_gallery }}" target="_blank" class="nav-link">&nbsp;&nbsp;<i class="fas fa-list-ul mg-nav1 font-20" aria-hidden="true"></i>&nbsp;&nbsp;RetainerGallery</a>
						@endif
						</li>
						@if($member->type == 'clinic')
							<li>
							@if(!$setting->hexa_price_list)
								<a class="nav-link">&nbsp;&nbsp;<i class="fas fa-list-ul mg-nav1 font-20" aria-hidden="true"></i>&nbsp;&nbsp;Pricelist</a>
							@else
								<a href="files/settings-{{ $setting->hexa_price_list }}" target="_blank" class="nav-link">&nbsp;&nbsp;<i class="fas fa-list-ul mg-nav1 font-20" aria-hidden="true"></i>&nbsp;&nbsp;Pricelist</a>
							@endif
							</li>
						@endif
						<li><a href="{{ $setting->hexa_line }}" target="_blank" class="nav-link">&nbsp;&nbsp;<i class="fab fa-line mg-nav1" aria-hidden="true"></i>&nbsp;@hexaceram</a></li>
						<li><a href="{{ $setting->hexa_facebook }}" target="_blank" class="nav-link">&nbsp;&nbsp;<i class="fab fa-facebook-square mg-nav1" aria-hidden="true"></i>&nbsp;Hexa Ceram</a></li>
						<li class="has-children">
							<a class="bg-nav1 mg-0">{{ (!$member->username) ? $member->name : $member->username }}
                                @if(!$member->image)
                                    <img src="template/front/images/user_no_image.png" class="img-profile-navbar">
                                @else
                                    <img src="uploads/members/thumbnail/{{ $member->image ?? '' }}" class="img-profile-navbar">
                                @endif
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
								<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
								<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
								</svg>
							</a>
							<ul class="dropdown">
								<li><a href="#" class="my-profile" data-toggle="modal" data-target="#profile">My Profile</a></li>
								<!-- <li style="text-align:center;">
								{{ Form::open(['files' => true, 'url' => 'front/member/loginExternalSit', 'class' => 'mg-profile1', 'method' => 'POST']) }}

								<button id="btn-online-order-sit" type="submit" class="btn-pass1;" style="background: none!important;
								border: none;
								padding: 0!important;
								font-family: arial, sans-serif;
								text-decoration: underline;
								cursor: pointer;" >Online SIT</button>
								{{ Form::close() }}
								</li> -->
								<li><a href="front/logout">Log out</a></li>
							</ul>
							<input type="hidden" class="get-member-id" value="{{ $member->id }}" readonly>
							<input type="hidden" class="get-verify-key" value="{{ $member->customer_verify_key }}" readonly>
						</li>
					</ul>
				</nav>
			</div>
			<div class="col-6 d-inline-block d-xl-none ml-md-0 pd-nav1" style="position: relative; top: 0px;"><a href="#" class="site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a></div>
		</div>
	</div>
</header>

<!-- Modal Profile -->
<div class="modal modal-profile" id="profile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content radi-popup1" style="margin: 20px;">
			<div class="modal-header line-popup2">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body pd-popup7">
				<div class="col-12">
					{{ Form::open(['files' => true, 'url' => 'front/member/profile/'.$member->id, 'class' => 'mg-profile1', 'method' => 'POST']) }}
					<div class="row">
						<div class="col-md-4">
                            <div class="profile-left-area" style="border-right: 1px solid #524c4c; height:100%;">
								<div class="picture-container mb-3">
									<div class="picture">
										@if(!$member->image)
											<img src="template/front/images/user_no_image.png" class="picture-src" id="wizardPicturePreview">
										@else
											<img src="uploads/members/thumbnail/{{ $member->image ?? '' }}" class="picture-src" id="wizardPicturePreview">
										@endif
										<div id="upload-profile-avatar">
											<input type="file" id="wizard-picture" name="image" onchange="uploadAvatar(this);">
										</div>
									</div>
									<div class="camera-pro"><i class="fa fa-camera size-icon2" aria-hidden="true"></i></div>
								</div>
                                <h4 class="text-center profile-name" title="{{ (!$member->username) ? $member->name : $member->username }}">
									{{ (!$member->username) ? $member->name : $member->username }}
								</h4>
                                <button class="btn-popup1 bg-blue text-white font-weight-normal edit-profile" type="button">แก้ไขโปรไฟล์</button>
                                <button type="button" class="btn-popup1" data-toggle="modal" data-target="#change_password" data-dismiss="modal">Change Password</button>
                            </div>
						</div>
						<div class="col-md-8">
							<div class="profile-right-area">
								{{ Form::text('username', $member->username ?? '', ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Username', 'readonly']) }}
								{{ Form::email('email', $member->email ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'E-mail', 'readonly']) }}
								{{ Form::text('phone', $member->phone ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Phone', 'readonly']) }}
								{{ Form::text('name', $member->name ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Clinic', 'readonly']) }}
								{{ Form::text('address', $member->address ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Address', 'readonly']) }}
								{{ Form::text('line_id', $member->line_id ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Line ID', 'readonly']) }}

								<p style="margin-top: 30px; font-size:18px"> การรับการแจ้งเตือน (Notification) <p>
								<p style="margin-bottom: 15px; font-size:12px; opacity: 0.6;"> *สำหรับแอพพลิเคชั่น Online Ordering กรณี Order มีการเปลี่ยนแปลง <p>
								{{ Form::email('emailNoti', $member->emailNoti ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'E-mail to receive Notification (ex. test@email.com)', 'readonly', 'required']) }}
								{{ Form::text('phoneNoti', $member->phoneNoti ?? '',  ['class' => 'profile-input edit-profile-color', 'placeholder' => 'Phone No.to receive Notification (ex. 0899999999)', 'readonly','required']) }}
								<div class="row modal-profile-submit">
									<button class="btn-rewa4 btn-suc1" type="button" data-dismiss="modal">Cancel</button>
									<button class="btn-rewa4 btn-suc1" type="submit" disabled>Submit</button>
								</div>
							</div>
						</div>
					</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Change Password -->
<div class="modal modal-change-password" id="change_password" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header line-popup2">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body pd-popup7">
				{{ Form::open(['url' => 'front/member/change_password/'.$member->id, 'method' => 'post'])}}
					{{ Form::password('password', ['class' => 'form-modal1 text-center', 'placeholder' => 'New Password', 'required'])}}
					{{ Form::password('password_confirmation', ['class' => 'form-modal1 text-center', 'placeholder' => 'Confirm New Password', 'required'])}}
                    <div class="row modal-change-password-submit">
                        <button class="btn-rewa4 btn-suc1" type="button" data-toggle="modal" data-target="#profile" data-dismiss="modal">Cancel</button>
                        <button class="btn-rewa4 btn-suc1" type="submit">Submit</button>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
